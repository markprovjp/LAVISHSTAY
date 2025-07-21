import { useState, useEffect, useRef } from 'react';
import { SendOutlined, MessageOutlined, CloseOutlined, UserOutlined } from '@ant-design/icons';
import { Input, Avatar, Button, FloatButton, Flex, Spin, Typography } from 'antd';
import { useSelector } from 'react-redux';
import { RootState } from '../../store';

const { Text } = Typography;

// Định nghĩa kiểu cho tin nhắn để code an toàn và dễ đọc hơn
interface Message {
    from: 'user' | 'bot';
    text: string;
}

const ChatBot = () => {
    const [isOpen, setIsOpen] = useState(false);
    const [inputValue, setInputValue] = useState('');
    const [messages, setMessages] = useState<Message[]>([
        { from: 'bot', text: 'Xin chào! Tôi có thể giúp gì cho bạn về đặt phòng tại LavishStay?' }
    ]);
    const [isLoading, setIsLoading] = useState(false);
    const messagesEndRef = useRef<HTMLDivElement>(null);

    // Lấy thông tin người dùng từ Redux store
    const { isAuthenticated, user } = useSelector((state: RootState) => state.auth);

    // Hàm để tự động cuộn xuống tin nhắn mới nhất
    const scrollToBottom = () => {
        messagesEndRef.current?.scrollIntoView({ behavior: 'smooth' });
    };

    useEffect(() => {
        scrollToBottom();
    }, [messages, isLoading]);

    const toggleChat = () => setIsOpen(!isOpen);

    const handleSend = () => {
        const trimmedInput = inputValue.trim();
        if (!trimmedInput) return;

        // Thêm tin nhắn của người dùng vào danh sách
        setMessages(prev => [...prev, { from: 'user', text: trimmedInput }]);
        setInputValue('');
        setIsLoading(true);

        // Giả lập bot đang xử lý và trả lời sau 1.5 giây
        setTimeout(() => {
            setIsLoading(false);
            setMessages(prev => [
                ...prev,
                { from: 'bot', text: 'Cảm ơn bạn đã hỏi. Hiện tại, tôi đang được phát triển và sẽ sớm có thể trả lời câu hỏi của bạn.' },
            ]);
        }, 1500);
    };

    // Component cho một tin nhắn
    const MessageBubble = ({ msg }: { msg: Message }) => {
        const isUser = msg.from === 'user';
        return (
            <Flex gap="small" align="flex-start" justify={isUser ? 'flex-end' : 'flex-start'}>
                {!isUser && <Avatar src="images/chatbot-img.png" size="large" />}
                <div
                    className={`
            px-4 py-2 rounded-2xl max-w-[85%] text-sm
            ${isUser ? 'bg-blue-500 text-white rounded-br-none' : 'bg-white border border-gray-200 rounded-bl-none'}
          `}
                >
                    <Text style={{ color: isUser ? 'white' : 'inherit' }}>{msg.text}</Text>
                </div>
                {isUser && (
                    <Avatar
                        src={isAuthenticated ? user?.avatar : undefined}
                        icon={!isAuthenticated || !user?.avatar ? <UserOutlined /> : undefined}
                        size="large"
                        className="bg-blue-100 text-blue-600"
                    >
                        {!isAuthenticated && "You"}
                    </Avatar>
                )}
            </Flex>
        );
    };

    // Component cho chỉ báo "Bot đang gõ"
    const TypingIndicator = () => (
        <Flex gap="small" align="flex-start" justify="flex-start">
            <Avatar src="images/chatbot-img.png" size="large" />
            <div className="bg-white border border-gray-200  ">
                <Spin size="small" />
            </div>
        </Flex>
    );

    return (
        <>
            <FloatButton
                icon={<MessageOutlined />}
                type="primary"
                onClick={toggleChat}
                style={{ right: 24, bottom: 110 }}
                badge={{ dot: true }}
            />

            <div
                className={`fixed bottom-32 right-12 z-50 w-96 h-[70vh] max-h-[600px] bg-gray-50 rounded-2xl shadow-2xl flex flex-col transition-all duration-300 ease-in-out origin-bottom-right ${isOpen ? 'opacity-100 scale-100' : 'opacity-0 scale-0 pointer-events-none'}`}
            >
                {/* Header */}
                <Flex align="center" justify="space-between" className="bg-blue-600 text-white p-4 rounded-t-2xl shadow-md">
                    <Flex align="center" gap="middle">
                        <Avatar src="images/chatbot-img.png" size="large" />
                        <div>
                            <Text strong style={{ color: 'white' }}>LavishStay AI Assistant</Text>
                            <Flex align="center" gap={4}>
                                <div className="w-2 h-2 bg-green-400 rounded-full" />
                                <Text style={{ color: 'white', opacity: 0.8 }} className="text-xs">Online</Text>
                            </Flex>
                        </div>
                    </Flex>
                    <Button type="text" icon={<CloseOutlined />} onClick={toggleChat} className="text-white/80 hover:text-white" />
                </Flex>

                {/* Message list */}
                <div className="flex-1 overflow-y-auto p-4 space-y-4">
                    {messages.map((msg, idx) => <MessageBubble key={idx} msg={msg} />)}
                    {isLoading && <TypingIndicator />}
                    <div ref={messagesEndRef} />
                </div>

                {/* Input area */}
                <div className="p-4 bg-white border-t border-gray-200">
                    <Flex gap="small">
                        <Input
                            placeholder="Nhập câu hỏi của bạn..."
                            value={inputValue}
                            onChange={(e) => setInputValue(e.target.value)}
                            onPressEnter={handleSend}
                            disabled={isLoading}
                            size="large"
                            autoFocus
                        />
                        <Button
                            type="primary"
                            icon={<SendOutlined />}
                            size="large"
                            onClick={handleSend}
                            loading={isLoading}
                            disabled={!inputValue.trim()}
                        />
                    </Flex>
                </div>
            </div>
        </>
    );
};

export default ChatBot;
