import React, { useState, useEffect } from 'react';
import { Modal, Form, Select, Input, InputNumber, Upload, Button, message, Space, Radio } from 'antd';
import { UploadOutlined } from '@ant-design/icons';

const { TextArea } = Input;

interface Policy {
    compensation_policy_id: number;
    name: string;
}

interface Props {
    visible: boolean;
    bookingId: number | null;
    policies: Policy[];
    onClose: () => void;
    onSuccess?: () => void;
}

const FALLBACK_TOKEN = 'Bearer 16|2SGfvsE87rTmPmUUDzkxwWa0nbMqHoBhi5aakmNwe24d85d8'; // fallback only

const CompensationRequestModal: React.FC<Props> = ({ visible, bookingId, policies, onClose, onSuccess }) => {
    const [submitting, setSubmitting] = useState(false);
    const [form] = Form.useForm();
    const [fileList, setFileList] = useState<any[]>([]);
    const [currentUser, setCurrentUser] = useState<any | null>(null);

    useEffect(() => {
        // try to read logged in user from localStorage (app stores authUser/authToken)
        try {
            const raw = localStorage.getItem('authUser') || localStorage.getItem('user') || null;
            if (raw) setCurrentUser(JSON.parse(raw));
        } catch (e) {
            setCurrentUser(null);
        }

        // if user present, prefill requested_by
        if (form) {
            const raw = localStorage.getItem('authUser') || localStorage.getItem('user') || null;
            try {
                const parsed = raw ? JSON.parse(raw) : null;
                if (parsed && parsed.id) {
                    form.setFieldsValue({ requested_by: parsed.id });
                    setCurrentUser(parsed);
                }
            } catch (e) { }
        }
    }, [form]);

    const handleFinish = async (values: any) => {
        if (!bookingId) return message.error('Booking ID không hợp lệ');

        setSubmitting(true);
        try {
            const formData = new FormData();
            formData.append('policy_id', String(values.policy_id));
            if (values.custom_reason) formData.append('custom_reason', values.custom_reason);
            if (values.requested_amount !== undefined && values.requested_amount !== null) formData.append('requested_amount', String(values.requested_amount));
            if (values.requested_by) formData.append('requested_by', String(values.requested_by));

            // attachments (files)
            // attachments: use Upload fileList state
            const files: File[] = fileList.map((f) => f.originFileObj || f);
            files.forEach((f) => {
                if (f) formData.append('attachments[]', f);
            });

            const token = localStorage.getItem('authToken') || localStorage.getItem('accessToken');
            const resp = await fetch(`http://127.0.0.1:8888/api/reception/bookings/${bookingId}/checkout/compensation`, {
                method: 'POST',
                headers: token ? { 'Authorization': `Bearer ${token}` } : { 'Authorization': FALLBACK_TOKEN },
                body: formData
            });

            const data = await resp.json().catch(() => null);
            if (!resp.ok) {
                message.error(data?.message || 'Tạo yêu cầu thất bại');
                return;
            }

            message.success('Tạo yêu cầu bồi thường thành công');
            form.resetFields();
            setFileList([]);
            onSuccess?.();
            onClose();
        } catch (err) {
            console.error('Create compensation error', err);
            message.error('Có lỗi khi gửi yêu cầu');
        } finally {
            setSubmitting(false);
        }
    };

    return (
        <Modal
            title="Tạo yêu cầu bồi thường"
            open={visible}
            onCancel={() => { setFileList([]); onClose(); }}
            footer={null}
            width={720}
        >
            <Form
                form={form}
                layout="vertical"
                onFinish={handleFinish}
                initialValues={{
                    policy_id: policies && policies.length ? policies[0].compensation_policy_id : undefined,
                    mode: policies && policies.length ? 'policy' : 'custom'
                }}
            >
                <Form.Item label="Loại yêu cầu">
                    <Radio.Group value={form.getFieldValue('mode') || (policies && policies.length ? 'policy' : 'custom')} onChange={(e) => {
                        form.setFieldsValue({ mode: e.target.value });
                    }}>
                        <Radio value="policy">Chọn chính sách</Radio>
                        <Radio value="custom">Khác (nhập lý do)</Radio>
                    </Radio.Group>
                </Form.Item>

                <Form.Item shouldUpdate>
                    {() => {
                        const currentMode = form.getFieldValue('mode') || (policies && policies.length ? 'policy' : 'custom');
                        return currentMode === 'policy' ? (
                            <Form.Item name="policy_id" label="Chọn chính sách" rules={[{ required: true, message: 'Bạn phải chọn chính sách' }]}>
                                <Select placeholder="Chọn chính sách bồi thường">
                                    {policies?.map(p => (
                                        <Select.Option key={p.compensation_policy_id} value={p.compensation_policy_id}>
                                            {p.name}
                                        </Select.Option>
                                    ))}
                                </Select>
                            </Form.Item>
                        ) : (
                            <Form.Item name="custom_reason" label="Lý do (bắt buộc)" rules={[{ required: true, message: 'Bạn phải nhập lý do' }]}>
                                <TextArea rows={3} placeholder="Mô tả lý do bồi thường" />
                            </Form.Item>
                        );
                    }}
                </Form.Item>

                <Form.Item name="requested_amount" label="Số tiền đề nghị (VND)">
                    <InputNumber style={{ width: '100%' }} min={0} />
                </Form.Item>

                <Form.Item name="requested_by" initialValue={currentUser?.id || undefined} hidden>
                    <Input />
                </Form.Item>

                <Form.Item name="attachments" label="Tệp đính kèm">
                    <Upload
                        multiple
                        listType="picture"
                        fileList={fileList}
                        onChange={({ fileList: newList }) => setFileList(newList)}
                        beforeUpload={() => false}
                        onPreview={async (file) => {
                            let src = file.url as string;
                            if (!src) {
                                src = await new Promise((resolve) => {
                                    const reader = new FileReader();
                                    if (file.originFileObj) {
                                        reader.readAsDataURL(file.originFileObj as Blob);
                                        reader.onload = () => resolve(String(reader.result));
                                    } else {
                                        resolve('');
                                    }
                                });
                            }
                            const img = new Image();
                            img.src = src;
                            const w = window.open(src, '_blank');
                            if (w) w.document.write(img.outerHTML);
                        }}
                    >
                        <Button icon={<UploadOutlined />}>Chọn tệp</Button>
                    </Upload>
                </Form.Item>

                <Form.Item>
                    <Space>
                        <Button onClick={onClose}>Huỷ</Button>
                        <Button type="primary" htmlType="submit" loading={submitting}>Gửi yêu cầu</Button>
                    </Space>
                </Form.Item>
            </Form>
        </Modal>
    );
};

export default CompensationRequestModal;
