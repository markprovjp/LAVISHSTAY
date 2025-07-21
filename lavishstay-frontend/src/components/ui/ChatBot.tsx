import { useState, useEffect, useRef } from 'react';
import { Input, Avatar, Button, FloatButton, Flex, Skeleton, Typography, Popover, Tag } from 'antd';
import { useSelector } from 'react-redux';
import { RootState } from '../../store';
import { MessageOutlined, SendOutlined, UserOutlined, CloseOutlined } from '@ant-design/icons';
import { AnimatePresence, motion } from 'framer-motion';

const { Text } = Typography;

// --- Type Definitions ---
interface Message {
    from: 'user' | 'bot';
    text: string;
}

// --- Constants ---
const quickQuestions = [
    "Giờ nhận phòng và trả phòng?",
    "Khách sạn có những tiện nghi gì?",
    "Tôi có thể xem thực đơn nhà hàng không?",
    "Chính sách hủy phòng như thế nào?",
];

// --- Sub-components for Cleaner Code ---

const MessageBubble = ({ msg }: { msg: Message }) => {
    const isUser = msg.from === 'user';
    const { isAuthenticated, user } = useSelector((state: RootState) => state.auth);

    return (
        <Flex gap="small" align="flex-start" justify={isUser ? 'flex-end' : 'flex-start'} className="mb-4">
            {!isUser && <Avatar src="/images/chatbot-img.png" size="large" />}
            <div
                className={`px-4 py-2 rounded-2xl max-w-[85%] text-sm shadow-sm ${isUser ? 'bg-[var(--color-primary)] text-white rounded-br-none' : 'bg-[var(--color-bg-container)] border border-[var(--color-border)] rounded-bl-none'}`}
            >
                <Text style={{ color: isUser ? 'white' : 'var(--color-text-base)' }}>{msg.text}</Text>
            </div>
            {isUser && (
                <Avatar
                    src={isAuthenticated ? user?.avatar : undefined}
                    icon={!isAuthenticated || !user?.avatar ? <UserOutlined /> : undefined}
                    size="large"
                    className="bg-gray-200 text-gray-600"
                />
            )}
        </Flex>
    );
};

const TypingIndicator = () => (
    <Flex gap="small" align="flex-start" justify="flex-start" className="mb-4">
        <Avatar src="/images/chatbot-img.png" size="large" />
        <div className="px-4 py-2 rounded-2xl bg-[var(--color-bg-container)] border border-[var(--color-border)] w-4/5">
            <Skeleton active paragraph={{ rows: 1, width: '100%' }} title={false} />
        </div>
    </Flex>
);

// --- Main ChatBot Component ---

const ChatBot = () => {
    const [popoverVisible, setPopoverVisible] = useState(false);
    const [inputValue, setInputValue] = useState('');
    const [messages, setMessages] = useState<Message[]>([
        { from: 'bot', text: 'Xin chào! Tôi là trợ lý AI của LavishStay. Tôi có thể giúp gì cho bạn?' }
    ]);
    const [isLoading, setIsLoading] = useState(false);
    // State to control the visibility of quick questions
    const [showInitialSuggestions, setShowInitialSuggestions] = useState(true);
    const chatContainerRef = useRef<HTMLDivElement>(null);

    useEffect(() => {
        if (chatContainerRef.current) {
            const { scrollHeight } = chatContainerRef.current;
            chatContainerRef.current.scrollTo({ top: scrollHeight, behavior: 'smooth' });
        }
    }, [messages, isLoading]);

    const handleSend = (textToSend?: string) => {
        // Hide suggestions on the first interaction
        if (showInitialSuggestions) {
            setShowInitialSuggestions(false);
        }

        const trimmedInput = (textToSend || inputValue).trim();
        if (!trimmedInput) return;

        setMessages(prev => [...prev, { from: 'user', text: trimmedInput }]);
        setInputValue('');
        setIsLoading(true);

        setTimeout(() => {
            setIsLoading(false);
            setMessages(prev => [
                ...prev,
                { from: 'bot', text: 'Cảm ơn câu hỏi của bạn. Hệ thống đang được phát triển và sẽ sớm cập nhật tính năng này.' },
            ]);
        }, 1500);
    };

    const chatContent = (
        <div className="w-[450px] h-[70vh] max-h-[600px] flex flex-col bg-[var(--color-bg-base)]">
            {/* Header */}
            <Flex align="center" justify="space-between" className="bg-[var(--color-primary)] text-white p-3 rounded-t-lg shadow-md flex-shrink-0">
                <Flex align="center" gap="middle">
                    <Avatar src="/images/chatbot-img.png" size="default" />
                    <div>
                        <Text strong style={{ color: 'white' }}>LavishStay Assistant</Text>
                        <Flex align="center" gap={4}>
                            <div className="w-2 h-2 bg-green-400 rounded-full" />
                            <Text style={{ color: 'white', opacity: 0.9 }} className="text-xs">Online</Text>
                        </Flex>
                    </div>
                </Flex>
                <Button type="text" icon={<CloseOutlined />} onClick={() => setPopoverVisible(false)} className="text-white/80 hover:text-white/80" />
            </Flex>

            {/* Messages Area */}
            <div ref={chatContainerRef} className="flex-1 overflow-y-auto p-4">
                {messages.map((msg, idx) => <MessageBubble key={idx} msg={msg} />)}

                {/* Initial Suggestions */}
                <AnimatePresence>
                    {showInitialSuggestions && (
                        <motion.div
                            initial={{ opacity: 0, y: 20 }}
                            animate={{ opacity: 1, y: 0 }}
                            exit={{ opacity: 0, height: 0, marginBottom: 0 }}
                            transition={{ duration: 0.3 }}
                            className="mb-4"
                        >
                            <Flex vertical gap="small">
                                <Text type="secondary">Hoặc chọn một trong các gợi ý sau:</Text>
                                <Flex gap="small" wrap="wrap">
                                    {quickQuestions.map((q, i) => (
                                        <Tag
                                            key={i}
                                            onClick={() => handleSend(q)}
                                            className="cursor-pointer transition-all hover:scale-105 border-[var(--color-primary)] bg-blue-50 text-[var(--color-primary)]"
                                        >
                                            {q}
                                        </Tag>
                                    ))}
                                </Flex>
                            </Flex>
                        </motion.div>
                    )}
                </AnimatePresence>

                {isLoading && <TypingIndicator />}
            </div>

            {/* Input Area */}
            <div className="p-3 bg-[var(--color-bg-container)] border-t border-[var(--color-border)] flex-shrink-0">
                <Flex gap="small">
                    <Input
                        placeholder="Nhập câu hỏi..."
                        value={inputValue}
                        onChange={(e) => setInputValue(e.target.value)}
                        onPressEnter={() => handleSend()}
                        disabled={isLoading}
                        size="large"
                        autoFocus
                    />
                    <Button
                        type="primary"
                        icon={<SendOutlined />}
                        size="large"
                        onClick={() => handleSend()}
                        loading={isLoading}
                        disabled={!inputValue.trim()}
                    />
                </Flex>
            </div>
        </div>
    );

    return (
        <Popover
            content={chatContent}
            trigger="click"
            open={popoverVisible}
            onOpenChange={setPopoverVisible}
            placement="topRight"
            arrow={false}
            overlayStyle={{ padding: 0, margin: 0, borderRadius: '8px', boxShadow: 'var(--box-shadow)' }}
        >
            <FloatButton
                icon={<MessageOutlined />}
                style={{ right: 24, bottom: 105 }}
                badge={{ dot: true }}
            />
        </Popover>
    );
};

export default ChatBot;