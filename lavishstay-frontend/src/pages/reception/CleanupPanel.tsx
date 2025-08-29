import React, { useState } from 'react';
import { Card, Button, Space, Row, Col, Typography, Modal, Input, Alert, Table, Statistic, Tag, message } from 'antd';
import { PlayCircleOutlined, EyeOutlined, ExclamationCircleOutlined, SafetyOutlined } from '@ant-design/icons';
import { cleanupAPI } from '../../utils/api';
import { useMutation, useQuery } from '@tanstack/react-query';

const { Title, Text, Paragraph } = Typography;
const { confirm } = Modal;

interface CleanupPreviewData {
    deleted?: number;
    deletedRooms?: number;
    deletedReps?: number;
    updated?: number;
    cleaningUpdated?: number;
    roomsCleared?: number;
    sample?: any[];
    sampleBookings?: any[];
    sampleRooms?: any[];
    summary?: {
        total_bookings_affected: number;
        total_rooms_affected: number;
    };
    expire_pending?: any;
    complete_checkouts?: any;
    complete_cleaning?: any;
}

const CleanupPanel: React.FC = () => {
    const [confirmationCode, setConfirmationCode] = useState('');
    const [showConfirmationModal, setShowConfirmationModal] = useState(false);
    const [currentOperation, setCurrentOperation] = useState<{
        type: string;
        previewData?: CleanupPreviewData;
    } | null>(null);

    // Preview queries
    const expirePendingQuery = useQuery({
        queryKey: ['cleanup', 'preview', 'expire-pending'],
        queryFn: cleanupAPI.previewExpirePending,
        refetchInterval: 30000, // Refresh every 30 seconds
    });

    const completePastCheckoutsQuery = useQuery({
        queryKey: ['cleanup', 'preview', 'complete-past-checkouts'],
        queryFn: cleanupAPI.previewCompletePastCheckouts,
        refetchInterval: 30000,
    });

    const completeCleaningBookingsQuery = useQuery({
        queryKey: ['cleanup', 'preview', 'complete-cleaning-bookings'],
        queryFn: cleanupAPI.previewCompleteCleaningBookings,
        refetchInterval: 30000,
    });

    const runAllQuery = useQuery({
        queryKey: ['cleanup', 'preview', 'run-all'],
        queryFn: cleanupAPI.previewRunAll,
        refetchInterval: 30000,
    });

    // Execute mutations
    const executeExpirePendingMutation = useMutation({
        mutationFn: (confirmationCode?: string) => cleanupAPI.executeExpirePending(confirmationCode),
        onSuccess: (data) => {
            message.success(data.message);
            // Refresh all previews
            expirePendingQuery.refetch();
            completePastCheckoutsQuery.refetch();
            completeCleaningBookingsQuery.refetch();
            runAllQuery.refetch();
        },
        onError: (error: any) => {
            message.error(error.response?.data?.message || 'Có lỗi xảy ra');
        },
    });

    const executeCompletePastCheckoutsMutation = useMutation({
        mutationFn: (confirmationCode?: string) => cleanupAPI.executeCompletePastCheckouts(confirmationCode),
        onSuccess: (data) => {
            message.success(data.message);
            expirePendingQuery.refetch();
            completePastCheckoutsQuery.refetch();
            completeCleaningBookingsQuery.refetch();
            runAllQuery.refetch();
        },
        onError: (error: any) => {
            message.error(error.response?.data?.message || 'Có lỗi xảy ra');
        },
    });

    const executeCompleteCleaningBookingsMutation = useMutation({
        mutationFn: (confirmationCode?: string) => cleanupAPI.executeCompleteCleaningBookings(confirmationCode),
        onSuccess: (data) => {
            message.success(data.message);
            expirePendingQuery.refetch();
            completePastCheckoutsQuery.refetch();
            completeCleaningBookingsQuery.refetch();
            runAllQuery.refetch();
        },
        onError: (error: any) => {
            message.error(error.response?.data?.message || 'Có lỗi xảy ra');
        },
    });

    const executeRunAllMutation = useMutation({
        mutationFn: (confirmationCode?: string) => cleanupAPI.executeRunAll(confirmationCode),
        onSuccess: (data) => {
            message.success(data.message);
            expirePendingQuery.refetch();
            completePastCheckoutsQuery.refetch();
            completeCleaningBookingsQuery.refetch();
            runAllQuery.refetch();
        },
        onError: (error: any) => {
            message.error(error.response?.data?.message || 'Có lỗi xảy ra');
        },
    });

    const getConfirmationCodeMutation = useMutation({
        mutationFn: ({ operation, count }: { operation: string; count: number }) =>
            cleanupAPI.getConfirmationCode(operation, count),
        onSuccess: (data) => {
            message.info(`Mã xác nhận: ${data.data.confirmation_code}`);
        },
        onError: (error: any) => {
            message.error(error.response?.data?.message || 'Không thể tạo mã xác nhận');
        },
    });

    const handleExecute = (
        type: string,
        executeFn: () => Promise<any>,
        previewData?: CleanupPreviewData,
        requiresConfirmation?: boolean
    ) => {
        if (requiresConfirmation) {
            // Store only the operation type and preview; don't store executeFn because it
            // may have closed over an empty confirmationCode. We'll call the correct
            // mutation with the latest confirmationCode when the user confirms.
            setCurrentOperation({ type, previewData });
            setShowConfirmationModal(true);
            setConfirmationCode('');
        } else {
            confirm({
                title: 'Xác nhận thực hiện',
                icon: <ExclamationCircleOutlined />,
                content: 'Bạn có chắc chắn muốn thực hiện thao tác này?',
                onOk: executeFn,
            });
        }
    };

    const handleConfirmationExecute = async () => {
        if (!currentOperation) return;

        try {
            // Call the correct mutation with the current confirmation code value
            switch (currentOperation.type) {
                case 'expire_pending':
                    await executeExpirePendingMutation.mutateAsync(confirmationCode);
                    break;
                case 'complete_checkouts':
                    await executeCompletePastCheckoutsMutation.mutateAsync(confirmationCode);
                    break;
                case 'complete_cleaning':
                    await executeCompleteCleaningBookingsMutation.mutateAsync(confirmationCode);
                    break;
                case 'run_all':
                    await executeRunAllMutation.mutateAsync(confirmationCode);
                    break;
                default:
                    break;
            }

            setShowConfirmationModal(false);
            setCurrentOperation(null);
            setConfirmationCode('');
        } catch (error) {
            // Error is handled by mutation onError
        }
    };

    const generateConfirmationCode = () => {
        if (!currentOperation?.previewData) return;

        let count = 0;
        let operation = '';

        switch (currentOperation.type) {
            case 'expire_pending':
                count = currentOperation.previewData.deleted || 0;
                operation = 'expire_pending';
                break;
            case 'complete_checkouts':
                count = currentOperation.previewData.updated || 0;
                operation = 'complete_checkouts';
                break;
            case 'complete_cleaning':
                count = currentOperation.previewData.cleaningUpdated || 0;
                operation = 'complete_cleaning';
                break;
            case 'run_all':
                count = currentOperation.previewData.summary?.total_bookings_affected || 0;
                operation = 'run_all';
                break;
        }

        getConfirmationCodeMutation.mutate({ operation, count });
    };

    const renderSampleTable = (data: any, title: string) => {
        // Normalize incoming data into an array. Some API responses return an object
        // or a single record instead of an array; Ant Design Table expects an array
        // (it will call .some internally) so we must coerce safely.
        const rows: any[] = Array.isArray(data) ? data : (data ? [data] : []);
        if (!rows || rows.length === 0) return null;

        const columns = Object.keys(rows[0]).map(key => ({
            title: key,
            dataIndex: key,
            key,
            render: (value: any) => {
                if (key === 'status') {
                    return <Tag color={value === 'Pending' ? 'orange' : 'blue'}>{value}</Tag>;
                }
                return value;
            }
        }));

        // Ensure each row has a stable key for Table
        const dataSource = rows.map((r, idx) => ({ key: r.id ?? r.booking_id ?? r.room_id ?? idx, ...r }));

        return (
            <div style={{ marginTop: 16 }}>
                <Text strong>{title}</Text>
                <Table
                    size="small"
                    dataSource={dataSource}
                    columns={columns}
                    pagination={false}
                    scroll={{ x: true }}
                    style={{ marginTop: 8 }}
                />
            </div>
        );
    };

    return (
        <div style={{ padding: 24, marginTop: 30 }}>
            <Title level={2}>
                <SafetyOutlined /> Hệ thống Dọn dẹp Tự động
            </Title>
            <Paragraph>
                Quản lý các thao tác dọn dẹp dữ liệu booking và phòng. Hệ thống sẽ hiển thị preview trước khi thực hiện.
            </Paragraph>

            <Row gutter={[16, 16]}>
                {/* Expire Pending Bookings */}
                <Col xs={24} lg={12}>
                    <Card
                        title="Xóa Booking Pending Hết hạn"
                        extra={
                            <Space>
                                {/* <Button
                                    icon={<EyeOutlined />}
                                    onClick={() => expirePendingQuery.refetch()}
                                    loading={expirePendingQuery.isLoading}
                                >
                                    Preview
                                </Button> */}
                                <Button
                                    type="primary"
                                    danger
                                    icon={<PlayCircleOutlined />}
                                    disabled={!expirePendingQuery.data?.data?.deleted}
                                    loading={executeExpirePendingMutation.isPending}
                                    onClick={() =>
                                        handleExecute(
                                            'expire_pending',
                                            () => executeExpirePendingMutation.mutateAsync(confirmationCode),
                                            expirePendingQuery.data?.data,
                                            expirePendingQuery.data?.requiresConfirmation
                                        )
                                    }
                                >
                                    Thực hiện
                                </Button>
                            </Space>
                        }
                    >
                        {expirePendingQuery.data?.data && (
                            <>
                                <Row gutter={16}>
                                    <Col span={8}>
                                        <Statistic
                                            title="Booking sẽ xóa"
                                            value={expirePendingQuery.data.data.deleted || 0}
                                            valueStyle={{ color: expirePendingQuery.data.data.deleted ? '#cf1322' : '#52c41a' }}
                                        />
                                    </Col>
                                    <Col span={8}>
                                        <Statistic
                                            title="Phòng liên quan"
                                            value={expirePendingQuery.data.data.deletedRooms || 0}
                                        />
                                    </Col>
                                    <Col span={8}>
                                        <Statistic
                                            title="Người đại diện"
                                            value={expirePendingQuery.data.data.deletedReps || 0}
                                        />
                                    </Col>
                                </Row>

                                {expirePendingQuery.data.requiresConfirmation && (
                                    <Alert
                                        message="Cần xác nhận"
                                        description="Thao tác này ảnh hưởng nhiều bản ghi, cần mã xác nhận"
                                        type="warning"
                                        showIcon
                                        style={{ marginTop: 16 }}
                                    />
                                )}

                                {renderSampleTable(expirePendingQuery.data.data.sample || [], 'Mẫu booking sẽ xóa')}
                            </>
                        )}
                    </Card>
                </Col>

                {/* Complete Past Checkouts */}
                <Col xs={24} lg={12}>
                    <Card
                        title="Hoàn thành Checkout Quá hạn"
                        extra={
                            <Space>
                                {/* <Button
                                    icon={<EyeOutlined />}
                                    onClick={() => completePastCheckoutsQuery.refetch()}
                                    loading={completePastCheckoutsQuery.isLoading}
                                >
                                    Preview
                                </Button> */}
                                <Button
                                    type="primary"
                                    icon={<PlayCircleOutlined />}
                                    disabled={!completePastCheckoutsQuery.data?.data?.updated}
                                    loading={executeCompletePastCheckoutsMutation.isPending}
                                    onClick={() =>
                                        handleExecute(
                                            'complete_checkouts',
                                            () => executeCompletePastCheckoutsMutation.mutateAsync(confirmationCode),
                                            completePastCheckoutsQuery.data?.data,
                                            completePastCheckoutsQuery.data?.requiresConfirmation
                                        )
                                    }
                                >
                                    Thực hiện
                                </Button>
                            </Space>
                        }
                    >
                        {completePastCheckoutsQuery.data?.data && (
                            <>
                                <Row gutter={16}>
                                    <Col span={12}>
                                        <Statistic
                                            title="Booking → Completed"
                                            value={completePastCheckoutsQuery.data.data.updated || 0}
                                            valueStyle={{ color: completePastCheckoutsQuery.data.data.updated ? '#1890ff' : '#52c41a' }}
                                        />
                                    </Col>
                                </Row>

                                {completePastCheckoutsQuery.data.requiresConfirmation && (
                                    <Alert
                                        message="Cần xác nhận"
                                        description="Thao tác này ảnh hưởng nhiều bản ghi, cần mã xác nhận"
                                        type="warning"
                                        showIcon
                                        style={{ marginTop: 16 }}
                                    />
                                )}

                                {renderSampleTable(completePastCheckoutsQuery.data.data.sample || [], 'Mẫu booking sẽ hoàn thành')}
                            </>
                        )}
                    </Card>
                </Col>

                {/* Complete Cleaning Bookings */}
                <Col xs={24} lg={12}>
                    <Card
                        title="Hoàn thành Dọn dẹp"
                        extra={
                            <Space>
                                {/* <Button
                                    icon={<EyeOutlined />}
                                    onClick={() => completeCleaningBookingsQuery.refetch()}
                                    loading={completeCleaningBookingsQuery.isLoading}
                                >
                                    Preview
                                </Button> */}
                                <Button
                                    type="primary"
                                    icon={<PlayCircleOutlined />}
                                    disabled={!completeCleaningBookingsQuery.data?.data?.cleaningUpdated}
                                    loading={executeCompleteCleaningBookingsMutation.isPending}
                                    onClick={() =>
                                        handleExecute(
                                            'complete_cleaning',
                                            () => executeCompleteCleaningBookingsMutation.mutateAsync(confirmationCode),
                                            completeCleaningBookingsQuery.data?.data,
                                            completeCleaningBookingsQuery.data?.requiresConfirmation
                                        )
                                    }
                                >
                                    Thực hiện
                                </Button>
                            </Space>
                        }
                    >
                        {completeCleaningBookingsQuery.data?.data && (
                            <>
                                <Row gutter={16}>
                                    <Col span={12}>
                                        <Statistic
                                            title="Booking → Completed"
                                            value={completeCleaningBookingsQuery.data.data.cleaningUpdated || 0}
                                            valueStyle={{ color: completeCleaningBookingsQuery.data.data.cleaningUpdated ? '#1890ff' : '#52c41a' }}
                                        />
                                    </Col>
                                    <Col span={12}>
                                        <Statistic
                                            title="Phòng xóa thông tin dọn"
                                            value={completeCleaningBookingsQuery.data.data.roomsCleared || 0}
                                        />
                                    </Col>
                                </Row>

                                {completeCleaningBookingsQuery.data.requiresConfirmation && (
                                    <Alert
                                        message="Cần xác nhận"
                                        description="Thao tác này ảnh hưởng nhiều bản ghi, cần mã xác nhận"
                                        type="warning"
                                        showIcon
                                        style={{ marginTop: 16 }}
                                    />
                                )}

                                {renderSampleTable(completeCleaningBookingsQuery.data.data.sampleBookings || [], 'Mẫu booking cleaning sẽ hoàn thành')}
                                {renderSampleTable(completeCleaningBookingsQuery.data.data.sampleRooms || [], 'Mẫu phòng sẽ xóa thông tin dọn')}
                            </>
                        )}
                    </Card>
                </Col>

                {/* Run All Operations */}
                <Col xs={24} lg={12}>
                    <Card
                        title="Thực hiện Tất cả"
                        extra={
                            <Space>
                                {/* <Button
                                    icon={<EyeOutlined />}
                                    onClick={() => runAllQuery.refetch()}
                                    loading={runAllQuery.isLoading}
                                >
                                    Preview
                                </Button> */}
                                <Button
                                    type="primary"
                                    danger
                                    icon={<PlayCircleOutlined />}
                                    disabled={!runAllQuery.data?.data?.summary?.total_bookings_affected}
                                    loading={executeRunAllMutation.isPending}
                                    onClick={() =>
                                        handleExecute(
                                            'run_all',
                                            () => executeRunAllMutation.mutateAsync(confirmationCode),
                                            runAllQuery.data?.data,
                                            runAllQuery.data?.requiresConfirmation
                                        )
                                    }
                                >
                                    Thực hiện Tất cả
                                </Button>
                            </Space>
                        }
                    >
                        {runAllQuery.data?.data?.summary && (
                            <>
                                <Row gutter={16}>
                                    <Col span={12}>
                                        <Statistic
                                            title="Tổng Booking ảnh hưởng"
                                            value={runAllQuery.data.data.summary.total_bookings_affected}
                                            valueStyle={{ color: runAllQuery.data.data.summary.total_bookings_affected ? '#722ed1' : '#52c41a' }}
                                        />
                                    </Col>
                                    <Col span={12}>
                                        <Statistic
                                            title="Tổng Phòng ảnh hưởng"
                                            value={runAllQuery.data.data.summary.total_rooms_affected}
                                        />
                                    </Col>
                                </Row>

                                {runAllQuery.data.requiresConfirmation && (
                                    <Alert
                                        message="Cần xác nhận"
                                        description="Thao tác này ảnh hưởng rất nhiều bản ghi, cần mã xác nhận"
                                        type="error"
                                        showIcon
                                        style={{ marginTop: 16 }}
                                    />
                                )}

                                <div style={{ marginTop: 16 }}>
                                    <Text strong>Chi tiết:</Text>
                                    <ul style={{ marginTop: 8 }}>
                                        <li>Xóa {runAllQuery.data.data.expire_pending?.deleted || 0} booking pending</li>
                                        <li>Hoàn thành {runAllQuery.data.data.complete_checkouts?.updated || 0} checkout quá hạn</li>
                                        <li>Hoàn thành {runAllQuery.data.data.complete_cleaning?.cleaningUpdated || 0} booking đang dọn</li>
                                    </ul>
                                </div>
                            </>
                        )}
                    </Card>
                </Col>
            </Row>

            {/* Confirmation Modal */}
            <Modal
                title="Xác nhận Thực hiện"
                open={showConfirmationModal}
                onCancel={() => setShowConfirmationModal(false)}
                footer={[
                    <Button key="cancel" onClick={() => setShowConfirmationModal(false)}>
                        Hủy
                    </Button>,
                    <Button
                        key="generate"
                        onClick={generateConfirmationCode}
                        loading={getConfirmationCodeMutation.isPending}
                    >
                        Tạo mã xác nhận
                    </Button>,
                    <Button
                        key="confirm"
                        type="primary"
                        danger
                        disabled={!confirmationCode}
                        loading={
                            executeExpirePendingMutation.isPending ||
                            executeCompletePastCheckoutsMutation.isPending ||
                            executeCompleteCleaningBookingsMutation.isPending ||
                            executeRunAllMutation.isPending
                        }
                        onClick={handleConfirmationExecute}
                    >
                        Xác nhận thực hiện
                    </Button>,
                ]}
            >
                <Alert
                    message="Thao tác nguy hiểm"
                    description={`Thao tác này sẽ ảnh hưởng đến nhiều bản ghi. Vui lòng nhập mã xác nhận để tiếp tục.`}
                    type="error"
                    showIcon
                    style={{ marginBottom: 16 }}
                />

                <Space direction="vertical" style={{ width: '100%' }}>
                    <Text>
                        Nhập mã xác nhận (nhấn "Tạo mã xác nhận" để lấy mã):
                    </Text>
                    <Input
                        placeholder="Nhập mã xác nhận 6 ký tự"
                        value={confirmationCode}
                        onChange={(e) => setConfirmationCode(e.target.value)}
                        maxLength={6}
                    />
                </Space>
            </Modal>
        </div>
    );
};

export default CleanupPanel;
