import { useState, useEffect, useRef } from 'react';
import { Input, Avatar, Button, FloatButton, Flex, Skeleton, Typography, Popover, Tag, InputRef, message } from 'antd';
import { useSelector } from 'react-redux';
import { RootState } from '../../store';
import { sendChatMessage, getChatHistory, getNewMessages } from '../../services/chatService';
import { MessageOutlined, SendOutlined, UserOutlined, CloseOutlined, RobotOutlined, CustomerServiceOutlined } from '@ant-design/icons';
import { AnimatePresence, motion } from 'framer-motion';

const { Text } = Typography;

// --- Type Definitions ---
interface Message {
    id: number;
    message: string;
    sender_type: 'user' | 'staff' | 'guest';
    is_from_bot: boolean;
    created_at: string;
}

// --- Constants ---
const quickQuestions = [
    "Giờ nhận phòng và trả phòng?",
    "Khách sạn có những tiện nghi gì?",
    "Tôi có thể xem thực đơn nhà hàng không?",
    "Chính sách hủy phòng như thế nào?",
];

// --- Sub-components ---
const MessageBubble = ({ msg }: { msg: Message }) => {
    const isUser = msg.sender_type === 'user' || msg.sender_type === 'guest';
    const isBot = msg.is_from_bot;
    const { isAuthenticated, user } = useSelector((state: RootState) => state.auth);

    return (
        <Flex gap="small" align="flex-start" justify={isUser ? 'flex-end' : 'flex-start'} className="mb-4">
            {!isUser && (
                <Avatar 
                    src={isBot ? undefined : "/images/chatbot-img.png"} 
                    icon={isBot ? <RobotOutlined /> : <CustomerServiceOutlined />}
                    size="large"
                    className={isBot ? 'bg-purple-500' : 'bg-blue-500'}
                />
            )}
            <div className="flex flex-col max-w-[85%]">
                {isBot && (
                    <div className="flex items-center mb-1 text-xs text-purple-600">
                        <RobotOutlined className="mr-1" />
                        <span>Bot tự động</span>
                    </div>
                )}
                <div
                    className={`px-4 py-2 rounded-2xl text-sm shadow-sm ${
                        isUser 
                            ? 'bg-[var(--color-primary)] text-white rounded-br-none' 
                            : isBot
                            ? 'bg-purple-50 border border-purple-200 rounded-bl-none text-purple-900'
                            : 'bg-[var(--color-bg-container)] border border-[var(--color-border)] rounded-bl-none'
                    }`}
                >
                    <Text style={{ color: isUser ? 'white' : isBot ? '#581c87' : 'var(--color-text-base)' }}>
                        {msg.message}
                    </Text>
                </div>
                <div className={`text-xs text-gray-400 mt-1 ${isUser ? 'text-right' : 'text-left'}`}>
                    {new Date(msg.created_at).toLocaleTimeString('vi-VN', { 
                        hour: '2-digit', 
                        minute: '2-digit' 
                    })}
                </div>
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
        <Avatar icon={<RobotOutlined />} size="large" className="bg-purple-500" />
        <div className="px-4 py-2 rounded-2xl bg-[var(--color-bg-container)] border border-[var(--color-border)] w-4/5">
            <Skeleton active paragraph={{ rows: 1, width: '100%' }} title={false} />
        </div>
    </Flex>
);

// --- Main ChatBot Component ---
const ChatBot = () => {
    const [popoverVisible, setPopoverVisible] = useState(false);
    const [inputValue, setInputValue] = useState('');
    const [isLoading, setIsLoading] = useState(false);
    const [showInitialSuggestions, setShowInitialSuggestions] = useState(true);
    const [messages, setMessages] = useState<Message[]>([]);
    const [conversationId, setConversationId] = useState<number | null>(null);
    const [clientToken, setClientToken] = useState<string | null>(null);
    const [unreadCount, setUnreadCount] = useState(0);

    const chatContainerRef = useRef<HTMLDivElement>(null);
    const inputRef = useRef<InputRef>(null);
    const pollingRef = useRef<NodeJS.Timeout | null>(null);

    // Load chat history on component mount
    useEffect(() => {
        loadChatHistory();
        
        // Get client token from localStorage
        const savedToken = localStorage.getItem('chat_client_token');
        const savedConversationId = localStorage.getItem('chat_conversation_id');
        
        if (savedToken) {
            setClientToken(savedToken);
        }
        
        if (savedConversationId) {
            setConversationId(parseInt(savedConversationId));
        }
    }, []);

    // Start polling when chat is open and conversation exists
    useEffect(() => {
        if (popoverVisible && conversationId) {
            startPolling();
        } else {
            stopPolling();
        }

        return () => stopPolling();
    }, [popoverVisible, conversationId]);

    // Effect for scrolling
    useEffect(() => {
        if (chatContainerRef.current) {
            const { scrollHeight } = chatContainerRef.current;
            chatContainerRef.current.scrollTo({ top: scrollHeight, behavior: 'smooth' });
        }
    }, [messages, isLoading]);

    // Effect for focusing input
    useEffect(() => {
        if (!isLoading && popoverVisible && inputRef.current) {
            inputRef.current.focus();
        }
    }, [isLoading, popoverVisible]);

    const loadChatHistory = async () => {
        try {
            const history = await getChatHistory();
            if (history && history.conversation.messages.length > 0) {
                setMessages(history.conversation.messages);
                setConversationId(history.conversation.id);
                setShowInitialSuggestions(false);
            } else {
                // Set initial bot message
                setMessages([{
                    id: 0,
                    message: 'Xin chào! Tôi là trợ lý AI của LavishStay. Tôi có thể giúp gì cho bạn?',
                    sender_type: 'staff',
                    is_from_bot: true,
                    created_at: new Date().toISOString(),
                }]);
            }
        } catch (error) {
            console.error('Error loading chat history:', error);
            // Set initial bot message on error
            setMessages([{
                id: 0,
                message: 'Xin chào! Tôi là trợ lý AI của LavishStay. Tôi có thể giúp gì cho bạn?',
                sender_type: 'staff',
                is_from_bot: true,
                created_at: new Date().toISOString(),
            }]);
        }
    };

    const startPolling = () => {
        if (pollingRef.current || !conversationId || messages.length === 0) return;
        
        pollingRef.current = setInterval(async () => {
            try {
                const lastMessageId = messages[messages.length - 1]?.id || 0;
                const response = await getNewMessages(conversationId, lastMessageId, clientToken);
                
                if (response.messages.length > 0) {
                    setMessages(prev => [...prev, ...response.messages]);
                    
                    // Update unread count if chat is closed
                    if (!popoverVisible) {
                        setUnreadCount(prev => prev + response.messages.length);
                    }
                }
            } catch (error) {
                console.error('Error polling messages:', error);
            }
        }, 3000);
    };

    const stopPolling = () => {
        if (pollingRef.current) {
            clearInterval(pollingRef.current);
            pollingRef.current = null;
        }
    };

    const handleSend = async (textToSend?: string) => {
        const trimmedInput = (textToSend || inputValue).trim();
        if (!trimmedInput || isLoading) return;

        if (showInitialSuggestions) {
            setShowInitialSuggestions(false);
        }

        setInputValue('');
        setIsLoading(true);

        try {
            const response = await sendChatMessage(trimmedInput, clientToken);
            
                        if (response.success) {
                // Update messages with response
                setMessages(prev => [...prev, ...response.messages]);
                
                // Update conversation state
                setConversationId(response.conversation_id);
                setClientToken(response.client_token);
                
                // Show escalation message if needed
                if (response.escalated) {
                    message.info(response.message || 'Câu hỏi của bạn đã được chuyển đến nhân viên hỗ trợ.');
                }
            }
        } catch (error) {
            console.error("Error sending chat message:", error);
            message.error('Có lỗi xảy ra khi gửi tin nhắn. Vui lòng thử lại.');
        } finally {
            setIsLoading(false);
        }
    };

    const handleKeyPress = (e: React.KeyboardEvent) => {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            handleSend();
        }
    };

    const toggleChat = () => {
        setPopoverVisible(!popoverVisible);
        if (!popoverVisible) {
            setUnreadCount(0);
        }
    };

    const chatContent = (
        <div className="w-[450px] h-[70vh] max-h-[600px] flex flex-col bg-[var(--color-bg-base)]">
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
                <Button 
                    type="text" 
                    icon={<CloseOutlined />} 
                    onClick={() => setPopoverVisible(false)} 
                    className="text-white/80 hover:text-white" 
                />
            </Flex>

            <div ref={chatContainerRef} className="flex-1 overflow-y-auto p-4">
                {messages.map((msg) => <MessageBubble key={msg.id} msg={msg} />)}
                
                <AnimatePresence>
                    {showInitialSuggestions && (
                        <motion.div 
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

            <div className="p-3 bg-[var(--color-bg-container)] border-t border-[var(--color-border)] flex-shrink-0">
                <Flex gap="small">
                    <Input
                        ref={inputRef}
                        placeholder="Nhập câu hỏi..."
                        value={inputValue}
                        onChange={(e) => setInputValue(e.target.value)}
                        onPressEnter={handleKeyPress}
                        disabled={isLoading}
                        size="large"
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
            onOpenChange={toggleChat}
            placement="topRight"
            arrow={false}
            overlayStyle={{ padding: 0, margin: 0, borderRadius: '8px', boxShadow: 'var(--box-shadow)' }}
        >
            <FloatButton
                icon={<MessageOutlined />}
                style={{ right: 24, bottom: 100 }}
                badge={{ count: unreadCount, overflowCount: 9 }}
                onClick={toggleChat}
            />
        </Popover>
    );
};

export default ChatBot;

