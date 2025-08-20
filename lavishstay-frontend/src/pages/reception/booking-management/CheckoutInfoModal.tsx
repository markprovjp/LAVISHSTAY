import React, { useEffect, useState } from 'react';
import { Modal, List, Row, Col, Typography, InputNumber, Button, Divider, Popconfirm, Space, message } from 'antd';
import { receptionAPI } from '../../../utils/api';

const { Text } = Typography;

type Props = {
    visible: boolean;
    bookingId: number | null;
    initialData?: any;
    onClose: () => void;
    onUpdated?: () => void;
    onAddServicesRequest?: () => void;
};

const CheckoutInfoModal: React.FC<Props> = ({ visible, bookingId, initialData, onClose, onUpdated, onAddServicesRequest }) => {
    const [data, setData] = useState<any>(initialData || null);
    const [loading, setLoading] = useState(false);
    const [editingQuantities, setEditingQuantities] = useState<Record<number, number>>({});

    useEffect(() => {
        if (!visible) return;
        if (initialData) {
            setData(initialData);
            const map: Record<number, number> = {};
            (initialData.booking_services?.services || []).forEach((s: any) => { map[s.service_id] = s.quantity; });
            setEditingQuantities(map);
            return;
        }
        // fetch fresh
        (async () => {
            if (!bookingId) return;
            setLoading(true);
            try {
                const res = await receptionAPI.getCheckoutInfo(bookingId);
                setData(res?.data || res);
                const map: Record<number, number> = {};
                (res?.data?.booking_services?.services || res?.booking_services?.services || []).forEach((s: any) => { map[s.service_id] = s.quantity; });
                setEditingQuantities(map);
            } catch (err) {
                console.error('Failed to load checkout info', err);
                message.error('Không thể tải thông tin check-out');
            } finally {
                setLoading(false);
            }
        })();
    }, [visible, bookingId, initialData]);

    const handleQuantityChange = (serviceId: number, qty: number) => {
        setEditingQuantities((s) => ({ ...s, [serviceId]: qty }));
    };

    const handleUpdateService = async (serviceId: number) => {
        if (!bookingId) return;
        const qty = editingQuantities[serviceId];
        setLoading(true);
        try {
            await receptionAPI.updateBookingService(bookingId, serviceId, { quantity: qty });
            message.success('Cập nhật dịch vụ thành công');
            onUpdated?.();
            // refresh
            const res = await receptionAPI.getCheckoutInfo(bookingId);
            setData(res?.data || res);
        } catch (err) {
            console.error('Update service failed', err);
            message.error('Cập nhật thất bại');
        } finally {
            setLoading(false);
        }
    };

    const handleRemoveService = async (serviceId: number) => {
        if (!bookingId) return;
        setLoading(true);
        try {
            await receptionAPI.removeBookingService(bookingId, serviceId);
            message.success('Xóa dịch vụ thành công');
            onUpdated?.();
            const res = await receptionAPI.getCheckoutInfo(bookingId);
            setData(res?.data || res);
        } catch (err) {
            console.error('Remove service failed', err);
            message.error('Xóa thất bại');
        } finally {
            setLoading(false);
        }
    };

    const handleConfirmCheckout = async () => {
        if (!bookingId) return;
        setLoading(true);
        try {
            await attemptCheckout({});
        } catch (err) {
            // errors handled inside attemptCheckout
        } finally {
            setLoading(false);
        }
    };

    const attemptCheckout = async (payload: any = {}) => {
        if (!bookingId) return;
        try {
            const res = await receptionAPI.processCheckout(bookingId, payload);
            // backend returns { success: true, ... } wrapped or direct
            message.success('Check-out đã được xử lý');
            onUpdated?.();
            onClose();
            return res;
        } catch (err: any) {
            console.error('Process checkout failed', err);
            const resp = err?.response?.data || err?.response || null;
            // Try to extract validation details
            const validation = resp?.validation_result || resp?.data?.validation_result || null;
            const canForce = resp?.can_force_checkout ?? resp?.data?.can_force_checkout ?? false;

            if (validation) {
                const blocking = validation.blocking_failures || validation.blocking_issues || [];
                const content = (
                    <div>
                        <div style={{ marginBottom: 8 }}>{resp?.message || 'Không thể check-out do có lỗi bắt buộc'}</div>
                        <ul>
                            {blocking.map((b: any, idx: number) => (
                                <li key={idx}><b>{b.rule_id}</b>: {b.message}</li>
                            ))}
                        </ul>
                    </div>
                );

                Modal.confirm({
                    title: 'Vấn đề khi check-out',
                    content,
                    okText: canForce ? 'Force check-out' : 'Đã hiểu',
                    cancelText: canForce ? 'Hủy' : undefined,
                    onOk: async () => {
                        if (canForce) {
                            setLoading(true);
                            try {
                                await receptionAPI.processCheckout(bookingId, { force_checkout: true });
                                message.success('Check-out đã được force và xử lý');
                                onUpdated?.();
                                onClose();
                            } catch (e) {
                                console.error('Force checkout failed', e);
                                message.error('Force check-out thất bại');
                            } finally {
                                setLoading(false);
                            }
                        }
                    }
                });

                return null;
            }

            // Fallback generic error
            message.error(resp?.message || 'Lỗi khi xử lý check-out');
            return null;
        }
    };

    return (
        <Modal
            title={`Check-out — Booking ${bookingId ?? ''}`}
            visible={visible}
            onCancel={onClose}
            footer={null}
            width={800}
            destroyOnClose
        >
            {!data || loading ? (
                <div>Đang tải...</div>
            ) : (
                <div>
                    <Row gutter={[12, 12]}>
                        <Col span={24}>
                            <Text strong>Thông tin khách:</Text>
                            <div style={{ marginTop: 8 }}>{data.guest_info?.guest_name} — {data.guest_info?.guest_phone}</div>
                        </Col>

                        <Col span={24} style={{ marginTop: 12 }}>
                            <Text strong>Dịch vụ đã xác nhận</Text>
                            <List
                                dataSource={data.booking_services?.services || []}
                                renderItem={(item: any) => (
                                    <List.Item>
                                        <List.Item.Meta
                                            title={<Text strong>{item.service_name}</Text>}
                                            description={<Text type="secondary">{item.service_description}</Text>}
                                        />
                                        <div style={{ marginLeft: 'auto', display: 'flex', alignItems: 'center', gap: 12 }}>
                                            <div style={{ minWidth: 120, textAlign: 'right' }}>
                                                <Text>{new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(item.unit_price_vnd ? Number(item.unit_price_vnd) : 0)}</Text>
                                            </div>

                                            <div>
                                                <InputNumber size="middle" style={{ width: 96 }} min={1} value={editingQuantities[item.service_id]} onChange={(v) => handleQuantityChange(item.service_id, Number(v || 0))} />
                                            </div>

                                            <Space>
                                                <Button size="small" type="primary" onClick={() => handleUpdateService(item.service_id)}>Cập nhật</Button>
                                                <Popconfirm title="Xóa dịch vụ này?" onConfirm={() => handleRemoveService(item.service_id)} okText="Xóa" cancelText="Hủy">
                                                    <Button danger size="small">Xóa</Button>
                                                </Popconfirm>
                                            </Space>
                                        </div>
                                    </List.Item>
                                )}
                            />
                        </Col>

                        <Col span={24} style={{ marginTop: 12 }}>
                            <Divider />
                            <Row justify="space-between" align="middle">
                                <Col>
                                    <div><Text type="secondary">Tổng phòng</Text></div>
                                    <div style={{ fontSize: 18 }}><Text strong>{new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(data.amount_calculation?.room_amount || 0)}</Text></div>
                                </Col>
                                <Col>
                                    <div><Text type="secondary">Tổng dịch vụ</Text></div>
                                    <div style={{ fontSize: 18 }}><Text strong>{new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(data.amount_calculation?.service_amount || 0)}</Text></div>
                                </Col>
                                <Col>
                                    <div><Text type="secondary">Tổng phải thu</Text></div>
                                    <div style={{ fontSize: 20 }}><Text strong style={{ color: '#f5222d' }}>{new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(data.amount_calculation?.total_amount || 0)}</Text></div>
                                </Col>
                            </Row>
                        </Col>

                        <Col span={24} style={{ marginTop: 18, textAlign: 'right' }}>
                            <Button style={{ marginRight: 8 }} onClick={onClose}>Đóng</Button>
                            <Button style={{ marginRight: 8 }} onClick={() => onAddServicesRequest?.()}>Thêm dịch vụ</Button>
                            <Button type="primary" onClick={handleConfirmCheckout}>Xác nhận Check-out</Button>
                        </Col>
                    </Row>
                </div>
            )}
        </Modal>
    );
};

export default CheckoutInfoModal;
