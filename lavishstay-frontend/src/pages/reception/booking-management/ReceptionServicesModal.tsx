import React, { useEffect, useMemo, useState } from 'react';
import { Modal, List, Checkbox, InputNumber, Row, Col, Typography, Button, Spin, message } from 'antd';
import { receptionAPI } from '../../../utils/api';

const { Text, Paragraph } = Typography;

type ServiceItem = {
    service_id: number;
    name: string;
    description?: string;
    price_vnd: string | number;
    unit?: string;
    formatted_price?: string;
    price_with_unit?: string;
};

type Props = {
    bookingId: number | null;
    visible: boolean;
    onClose: () => void;
    onAdded?: (checkoutInfo?: any) => void; // called after successfully adding services
};

const ReceptionServicesModal: React.FC<Props> = ({ bookingId, visible, onClose, onAdded }) => {
    const [services, setServices] = useState<ServiceItem[]>([]);
    const [loading, setLoading] = useState(false);
    const [selected, setSelected] = useState<Record<number, number>>({});
    const [checkingOut, setCheckingOut] = useState(false);

    useEffect(() => {
        if (!visible) return;
        setLoading(true);
        receptionAPI
            .getAvailableServices()
            .then((res) => {
                const data = res?.data?.services || res?.services || res?.data || [];
                setServices(Array.isArray(data) ? data : []);
            })
            .catch((err) => {
                console.error('Failed to load services', err);
                message.error('Không thể tải danh sách dịch vụ');
            })
            .finally(() => setLoading(false));
    }, [visible]);

    useEffect(() => {
        if (!visible) setSelected({});
    }, [visible]);

    const toggleService = (serviceId: number, checked: boolean) => {
        setSelected((s) => {
            const copy = { ...s };
            if (!checked) delete copy[serviceId];
            else copy[serviceId] = copy[serviceId] || 1;
            return copy;
        });
    };

    const setQuantity = (serviceId: number, qty: number | undefined) => {
        setSelected((s) => {
            const copy = { ...s };
            if (!qty || qty <= 0) delete copy[serviceId];
            else copy[serviceId] = qty;
            return copy;
        });
    };

    const selectedServices = useMemo(() => {
        return Object.entries(selected).map(([id, qty]) => ({ service_id: Number(id), quantity: qty }));
    }, [selected]);

    const subtotal = useMemo(() => {
        return Object.entries(selected).reduce((acc, [id, qty]) => {
            const svc = services.find((s) => Number(s.service_id) === Number(id));
            const price = svc ? Number(String(svc.price_vnd).replace(/[^0-9.-]+/g, '')) : 0;
            return acc + price * (qty || 0);
        }, 0);
    }, [selected, services]);

    const handleAddServices = async () => {
        if (!bookingId) {
            message.error('Booking ID không hợp lệ');
            return;
        }
        if (selectedServices.length === 0) {
            message.warning('Vui lòng chọn ít nhất 1 dịch vụ');
            return;
        }

        setCheckingOut(true);
        try {
            // Payload assumption: { services: [{ service_id, quantity }] }
            await receptionAPI.addBookingServices(bookingId, selectedServices);
            message.success('Thêm dịch vụ thành công');
            // Get checkout info and pass it back to parent
            try {
                const res = await receptionAPI.getCheckoutInfo(bookingId);
                onAdded?.(res);
            } catch (err) {
                console.warn('Could not fetch checkout info after adding services', err);
                onAdded?.();
            }
            onClose();
        } catch (err) {
            console.error('Failed to add services', err);
            message.error('Thêm dịch vụ thất bại');
        } finally {
            setCheckingOut(false);
        }
    };

    return (
        <Modal
            title="Chọn dịch vụ cho khách"
            visible={visible}
            onCancel={onClose}
            footer={null}
            width={820}
            destroyOnClose
        >
            {loading ? (
                <div style={{ textAlign: 'center', padding: 40 }}><Spin /></div>
            ) : (
                <>
                    <List
                        dataSource={services}
                        renderItem={(item: ServiceItem) => {
                            const id = Number(item.service_id);
                            const checked = typeof selected[id] === 'number';
                            const qty = selected[id] || 1;
                            const price = Number(String(item.price_vnd).replace(/[^0-9.-]+/g, '')) || 0;
                            return (
                                <List.Item>
                                    <Row style={{ width: '100%' }} align="middle">
                                        <Col span={14}>
                                            <Checkbox checked={checked} onChange={(e) => toggleService(id, e.target.checked)}>
                                                <Text strong style={{ fontSize: 14 }}>{item.name}</Text>
                                            </Checkbox>
                                            <Paragraph type="secondary" style={{ margin: '6px 0 0 28px' }}>{item.description}</Paragraph>
                                        </Col>
                                        <Col span={6} style={{ textAlign: 'right' }}>
                                            <Text strong style={{ fontSize: 14 }}>{item.formatted_price || new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(price)}</Text>
                                            <div style={{ color: '#888' }}>{item.unit ? `/${item.unit}` : item.price_with_unit}</div>
                                        </Col>
                                        <Col span={4} style={{ textAlign: 'right' }}>
                                            <InputNumber min={1} value={qty} disabled={!checked} onChange={(v) => setQuantity(id, Number(v || 0))} />
                                        </Col>
                                    </Row>
                                </List.Item>
                            );
                        }}
                    />

                    <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginTop: 16 }}>
                        <div>
                            <Text type="secondary">Tổng tiền dịch vụ:</Text>
                            <div style={{ fontSize: 18, fontWeight: 600, color: '#f5222d' }}>{new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(subtotal)}</div>
                        </div>

                        <div>
                            <Button style={{ marginRight: 8 }} onClick={onClose}>Hủy</Button>
                            <Button type="primary" loading={checkingOut} onClick={handleAddServices} disabled={!bookingId}>Thêm dịch vụ</Button>
                        </div>
                    </div>
                </>
            )}
        </Modal>
    );
};

export default ReceptionServicesModal;
