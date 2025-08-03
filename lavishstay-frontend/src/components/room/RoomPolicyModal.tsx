// src/components/room/RoomPolicyModal.tsx
import React from 'react';
import { Modal, Tag, Divider, Space } from 'antd';
import { motion } from 'framer-motion';
import {
    Clock,
    X,
    Ban,
    Cigarette,
    CreditCard,
    Users,
    CheckCircle,
    AlertCircle,
    Info,
    Shield
} from 'lucide-react';
import { RoomPolicy } from '../../types/roomDetail';
import { useRoomDetailStore } from '../../stores/roomDetailStore';

interface RoomPolicyModalProps {
    policies: RoomPolicy[];
    roomName: string;
}

// Icon mapping
const iconMap: { [key: string]: React.ReactNode } = {
    Clock: <Clock size={20} />,
    X: <X size={20} />,
    Ban: <Ban size={20} />,
    Cigarette: <Cigarette size={20} />,
    CreditCard: <CreditCard size={20} />,
    Users: <Users size={20} />,
    CheckCircle: <CheckCircle size={20} />,
    AlertCircle: <AlertCircle size={20} />,
    Info: <Info size={20} />,
    Shield: <Shield size={20} />,
};

const policyTypeConfig = {
    checkin: {
        title: 'Nhận phòng',
        color: 'blue',
        icon: <Clock size={20} />,
        description: 'Thông tin về thời gian và quy trình nhận phòng'
    },
    checkout: {
        title: 'Trả phòng',
        color: 'green',
        icon: <CheckCircle size={20} />,
        description: 'Thông tin về thời gian và quy trình trả phòng'
    },
    cancellation: {
        title: 'Hủy phòng',
        color: 'orange',
        icon: <X size={20} />,
        description: 'Chính sách hủy phòng và hoàn tiền'
    },
    pets: {
        title: 'Thú cưng',
        color: 'purple',
        icon: <Ban size={20} />,
        description: 'Quy định về việc mang thú cưng'
    },
    smoking: {
        title: 'Hút thuốc',
        color: 'red',
        icon: <Cigarette size={20} />,
        description: 'Quy định về hút thuốc trong phòng'
    },
    children: {
        title: 'Trẻ em',
        color: 'cyan',
        icon: <Users size={20} />,
        description: 'Chính sách dành cho trẻ em'
    },
    payment: {
        title: 'Thanh toán',
        color: 'gold',
        icon: <CreditCard size={20} />,
        description: 'Thông tin về phương thức thanh toán'
    },
};

const RoomPolicyModal: React.FC<RoomPolicyModalProps> = ({
    policies,
    roomName
}) => {
    const { isPolicyModalOpen, setPolicyModalOpen } = useRoomDetailStore();

    const handleClose = () => {
        setPolicyModalOpen(false);
    };

    // Group policies by type
    const groupedPolicies = policies.reduce((acc, policy) => {
        if (!acc[policy.type]) {
            acc[policy.type] = [];
        }
        acc[policy.type].push(policy);
        return acc;
    }, {} as Record<string, RoomPolicy[]>);

    const PolicySection: React.FC<{
        type: string;
        policies: RoomPolicy[];
        index: number
    }> = ({ type, policies, index }) => {
        const config = policyTypeConfig[type as keyof typeof policyTypeConfig];
        if (!config) return null;

        return (
            <motion.div
                initial={{ opacity: 0, y: 10 }}
                animate={{ opacity: 1, y: 0 }}
                transition={{ duration: 0.3, delay: index * 0.1 }}
                className="policy-section"
            >
                <div className="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                    <div className="flex items-center gap-3 mb-3">
                        <div className={`p-2 rounded-lg bg-${config.color}-100 dark:bg-${config.color}-900 text-${config.color}-600 dark:text-${config.color}-400`}>
                            {config.icon}
                        </div>
                        <div>
                            <h3 className="text-lg font-semibold text-gray-900 dark:text-white">
                                {config.title}
                            </h3>
                            <p className="text-sm text-gray-600 dark:text-gray-400">
                                {config.description}
                            </p>
                        </div>
                        <Tag color={config.color} className="ml-auto">
                            {policies.length} quy định
                        </Tag>
                    </div>

                    <div className="space-y-3">
                        {policies.map((policy) => (
                            <div
                                key={policy.id}
                                className="bg-white dark:bg-gray-600 p-4 rounded-lg border border-gray-200 dark:border-gray-500"
                            >
                                <div className="flex items-start gap-3">
                                    {policy.icon && (
                                        <div className="text-gray-600 dark:text-gray-400 mt-1">
                                            {iconMap[policy.icon] || <Info size={16} />}
                                        </div>
                                    )}
                                    <div className="flex-1">
                                        <h4 className="font-medium text-gray-900 dark:text-white mb-1">
                                            {policy.title}
                                        </h4>
                                        <p className="text-gray-700 dark:text-gray-300 text-sm leading-relaxed">
                                            {policy.description}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        ))}
                    </div>
                </div>
            </motion.div>
        );
    };

    return (
        <Modal
            title={
                <div className="flex items-center gap-3">
                    <Shield size={24} className="text-blue-600 dark:text-blue-400" />
                    <div>
                        <h2 className="text-xl font-bold text-gray-900 dark:text-white">
                            Chính sách phòng
                        </h2>
                        <p className="text-sm text-gray-600 dark:text-gray-400 font-normal">
                            {roomName}
                        </p>
                    </div>
                </div>
            }
            open={isPolicyModalOpen}
            onCancel={handleClose}
            footer={null}
            width={800}
            className="room-policy-modal"
            styles={{
                body: { maxHeight: '70vh', overflowY: 'auto' }
            }}
        >
            <div className="space-y-6">
                {/* Summary */}
                <div className="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg border border-blue-200 dark:border-blue-800">
                    <div className="flex items-start gap-3">
                        <Info size={20} className="text-blue-600 dark:text-blue-400 mt-1" />
                        <div>
                            <h3 className="font-semibold text-blue-800 dark:text-blue-200 mb-2">
                                Tóm tắt chính sách
                            </h3>
                            <ul className="text-sm text-blue-700 dark:text-blue-300 space-y-1">
                                <li>• Vui lòng đọc kỹ các chính sách trước khi đặt phòng</li>
                                <li>• Các chính sách có thể thay đổi tùy theo thời gian và loại phòng</li>
                                <li>• Liên hệ với chúng tôi nếu có bất kỳ thắc mắc nào</li>
                            </ul>
                        </div>
                    </div>
                </div>

                {/* Policies by Type */}
                <div className="space-y-6">
                    {Object.entries(groupedPolicies).map(([type, typePolicies], index) => (
                        <PolicySection
                            key={type}
                            type={type}
                            policies={typePolicies}
                            index={index}
                        />
                    ))}
                </div>

                <Divider />

                {/* Important Notes */}
                <div className="bg-amber-50 dark:bg-amber-900/20 p-4 rounded-lg border border-amber-200 dark:border-amber-800">
                    <div className="flex items-start gap-3">
                        <AlertCircle size={20} className="text-amber-600 dark:text-amber-400 mt-1" />
                        <div>
                            <h3 className="font-semibold text-amber-800 dark:text-amber-200 mb-2">
                                Lưu ý quan trọng
                            </h3>
                            <ul className="text-sm text-amber-700 dark:text-amber-300 space-y-1">
                                <li>• Vi phạm chính sách có thể dẫn đến phí phạt hoặc chấm dứt lưu trú</li>
                                <li>• Một số chính sách có thể có ngoại lệ trong trường hợp đặc biệt</li>
                                <li>• Khách hàng có trách nhiệm tuân thủ tất cả các quy định</li>
                                <li>• Chính sách này áp dụng cho tất cả khách lưu trú</li>
                            </ul>
                        </div>
                    </div>
                </div>

                {/* Contact Info */}
                <div className="text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <h4 className="font-semibold text-gray-900 dark:text-white mb-2">
                        Cần hỗ trợ thêm?
                    </h4>
                    <p className="text-sm text-gray-600 dark:text-gray-400 mb-3">
                        Liên hệ với đội ngũ hỗ trợ khách hàng của chúng tôi
                    </p>
                    <Space>
                        <Tag color="blue" className="cursor-pointer px-3 py-1">
                            📞 Hotline: 1900-xxxx
                        </Tag>
                        <Tag color="green" className="cursor-pointer px-3 py-1">
                            📧 Email: support@lavishstay.com
                        </Tag>
                    </Space>
                </div>
            </div>
        </Modal>
    );
};

export default RoomPolicyModal;
