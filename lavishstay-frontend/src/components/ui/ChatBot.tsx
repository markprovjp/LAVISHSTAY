import { useState, useEffect, useRef } from 'react';
import { Input, Avatar, Button, FloatButton, Flex, Skeleton, Typography, Popover, Tag, Upload, Tooltip, Image as AntImage } from 'antd';
import { useSelector } from 'react-redux';
import { RootState } from '../../store';
import { sendChatMessage, ChatMessage } from '../../services/chatService';
import { MessageOutlined, SendOutlined, UserOutlined, CloseOutlined, PaperClipOutlined } from '@ant-design/icons';
import { AnimatePresence, motion } from 'framer-motion';
import type { InputRef, UploadFile } from 'antd';
import type { Part } from "@google/generative-ai";

const { Text } = Typography;

// --- Constants ---
const quickQuestions = [
    "Khách sạn có hồ bơi không?",
    "Giờ nhận phòng là khi nào?",
    "Tôi có thể mang theo thú cưng không?",
    "Nhà hàng có những món gì?",
];
const CHAT_HISTORY_KEY = 'lavishstay_gemini_chat_history';

// --- Helper Function ---
const fileToGenerativePart = async (file: File): Promise<Part> => {
    const base64EncodedDataPromise = new Promise<string>((resolve) => {
        const reader = new FileReader();
        reader.onloadend = () => resolve((reader.result as string).split(',')[1]);
        reader.readAsDataURL(file);
    });
    return {
        inlineData: { data: await base64EncodedDataPromise, mimeType: file.type },
    };
};

// --- Sub-components ---
const MessageBubble = ({ msg }: { msg: ChatMessage }) => {
    const isUser = msg.role === 'user';
    const { isAuthenticated, user } = useSelector((state: RootState) => state.auth);
    const textPart = msg.parts.find(part => 'text' in part) as { text: string } | undefined;
    const imagePart = msg.parts.find(part => 'inlineData' in part) as Part | undefined;

    return (
        <Flex gap="small" align="flex-start" justify={isUser ? 'flex-end' : 'flex-start'} className="mb-4">
            {!isUser && <Avatar src="/images/chatbot-img.png" size="large" />}
            <div className={`px-4 py-2 rounded-2xl max-w-[85%] text-sm shadow-sm ${isUser ? 'bg-[var(--color-primary)] text-white rounded-br-none' : 'bg-[var(--color-bg-container)] border border-[var(--color-border)] rounded-bl-none'}`}>
                {imagePart && (
                    <div className="mb-2">
                        <AntImage
                            src={`data:${imagePart.inlineData?.mimeType};base64,${imagePart.inlineData?.data}`}
                            width={150}
                            className="rounded-md"
                        />
                    </div>
                )}
                {textPart && <Text style={{ color: isUser ? 'white' : 'var(--color-text-base)' }}>{textPart.text}</Text>}
            </div>
            {isUser && <Avatar src={isAuthenticated ? user?.avatar : undefined} icon={!isAuthenticated || !user?.avatar ? <UserOutlined /> : undefined} size="large" />}
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
    const [isLoading, setIsLoading] = useState(false);
    const [imageFile, setImageFile] = useState<UploadFile | null>(null);

    const [messages, setMessages] = useState<ChatMessage[]>(() => {
        try {
            const savedHistory = localStorage.getItem(CHAT_HISTORY_KEY);
            return savedHistory ? JSON.parse(savedHistory) : [];
        } catch (error) { return []; }
    });

    const chatContainerRef = useRef<HTMLDivElement>(null);
    const inputRef = useRef<InputRef>(null);

    useEffect(() => {
        if (chatContainerRef.current) {
            chatContainerRef.current.scrollTo({ top: chatContainerRef.current.scrollHeight, behavior: 'smooth' });
        }
    }, [messages, isLoading]);

    useEffect(() => {
        localStorage.setItem(CHAT_HISTORY_KEY, JSON.stringify(messages));
    }, [messages]);

    useEffect(() => {
        if (popoverVisible && !isLoading) inputRef.current?.focus();
    }, [popoverVisible, isLoading]);

    const handleSend = async (textToSend?: string) => {
        const currentMessage = (textToSend || inputValue).trim();
        if (!currentMessage && !imageFile) return;

        setIsLoading(true);
        setInputValue('');

        let imagePart: Part | undefined = undefined;
        if (imageFile?.originFileObj) {
            imagePart = await fileToGenerativePart(imageFile.originFileObj);
        }

        const userMessageParts: Part[] = [];
        if (currentMessage) userMessageParts.push({ text: currentMessage });
        if (imagePart) userMessageParts.push(imagePart);

        const fullHistory = [...messages, { role: 'user', parts: userMessageParts }] as ChatMessage[];
        setMessages(fullHistory);
        setImageFile(null);

        try {
            const botResponseText = await sendChatMessage(messages, currentMessage, imagePart);
            const botMessage: ChatMessage = { role: 'model', parts: [{ text: botResponseText }] };
            setMessages(prev => [...prev, botMessage]);
        } catch (err) {
            const errorMessage: ChatMessage = { role: 'model', parts: [{ text: 'Rất tiếc, đã có lỗi xảy ra. Vui lòng thử lại sau.' }] };
            setMessages(prev => [...prev, errorMessage]);
        } finally {
            setIsLoading(false);
        }
    };

    const chatContent = (
        <div className="w-[450px] h-[70vh] max-h-[600px] flex flex-col bg-[var(--color-bg-base)]">
            <Flex align="center" justify="space-between" className="bg-[var(--color-primary)] text-white p-3 rounded-t-lg shadow-md flex-shrink-0">
                <Flex align="center" gap="middle">
                    <Avatar src="/images/chatbot-img.png" />
                    <div>
                        <Text strong style={{ color: 'white' }}>LavishStay Assistant</Text>
                        <Flex align="center" gap={4}><div className="w-2 h-2 bg-green-400 rounded-full" /><Text className="text-xs text-white/90">Online</Text></Flex>
                    </div>
                </Flex>
                <Button type="text" icon={<CloseOutlined />} onClick={() => setPopoverVisible(false)} className="text-white/80 hover:text-white" />
            </Flex>

            <div ref={chatContainerRef} className="flex-1 overflow-y-auto p-4">
                {messages.length === 0 && (
                    <AnimatePresence>
                        <motion.div exit={{ opacity: 0, height: 0 }} className="mb-4">
                            <Flex vertical gap="small">
                                <Text type="secondary">Gợi ý cho bạn:</Text>
                                <Flex gap="small" wrap="wrap">
                                    {quickQuestions.map((q) => <Tag key={q} onClick={() => handleSend(q)} className="cursor-pointer">{q}</Tag>)}
                                </Flex>
                            </Flex>
                        </motion.div>
                    </AnimatePresence>
                )}
                {messages.map((msg, idx) => <MessageBubble key={idx} msg={msg} />)}
                {isLoading && <TypingIndicator />}
            </div>

            <div className="p-3 bg-[var(--color-bg-container)] border-t border-[var(--color-border)] flex-shrink-0">
                {imageFile && (
                    <div className="mb-2 p-2 border rounded-md relative w-fit">
                        <AntImage src={URL.createObjectURL(imageFile.originFileObj as File)} width={60} />
                        <Button icon={<CloseOutlined />} size="small" shape="circle" className="absolute -top-2 -right-2" onClick={() => setImageFile(null)} />
                    </div>
                )}
                <Flex gap="small">
                    <Upload
                        fileList={imageFile ? [imageFile] : []}
                        beforeUpload={(file) => {
                            setImageFile({ uid: file.uid, name: file.name, originFileObj: file });
                            return false; // Prevent auto-upload
                        }}
                        showUploadList={false}
                        accept="image/*"
                    >
                        <Tooltip title="Gửi ảnh">
                            <Button icon={<PaperClipOutlined />} disabled={!!imageFile} />
                        </Tooltip>
                    </Upload>
                    <Input ref={inputRef} placeholder="Nhập câu hỏi..." value={inputValue} onChange={(e) => setInputValue(e.target.value)} onPressEnter={() => handleSend()} disabled={isLoading} />
                    <Button type="primary" icon={<SendOutlined />} onClick={() => handleSend()} loading={isLoading} disabled={!inputValue.trim() && !imageFile} />
                </Flex>
            </div>
        </div>
    );

    return (
        <Popover content={chatContent} trigger="click" open={popoverVisible} onOpenChange={setPopoverVisible} placement="topRight" arrow={false} overlayStyle={{ padding: 0 }}>
            <FloatButton icon={<MessageOutlined />} style={{ right: 24, bottom: 100 }} badge={{ dot: true }} />
        </Popover>
    );
};

export default ChatBot;