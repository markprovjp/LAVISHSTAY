import api from '../utils/api';

// --- Type Definitions for API communication ---

/**
 * Payload sent to the chat API.
 */
interface ChatPayload {
  message: string;
  client_token?: string;
}

/**
 * Expected response from the chat API.
 */
interface ChatResponse {
  success: boolean;
  conversation_id: number;
  client_token: string;
  escalated?: boolean;
  message?: string;
  messages: Array<{
    id: number;
    message: string;
    sender_type: 'user' | 'staff' | 'guest';
    is_from_bot: boolean;
    created_at: string;
  }>;
}

/**
 * Response for getting conversation
 */
interface ConversationResponse {
  success: boolean;
  conversation: {
    id: number;
    status: string;
    messages: Array<{
      id: number;
      message: string;
      sender_type: 'user' | 'staff' | 'guest';
      is_from_bot: boolean;
      created_at: string;
    }>;
  };
}

/**
 * Response for new messages
 */
interface NewMessagesResponse {
  success: boolean;
  messages: Array<{
    id: number;
    message: string;
    sender_type: 'user' | 'staff' | 'guest';
    is_from_bot: boolean;
    created_at: string;
  }>;
}

/**
 * Retrieves the client token from localStorage or creates a new one.
 * This token uniquely identifies the user's device/browser session.
 * @returns {string} The client token.
 */
export function getClientToken(): string {
  let clientToken = localStorage.getItem("chat_client_token");
  if (!clientToken) {
    clientToken = crypto.randomUUID();
    localStorage.setItem("chat_client_token", clientToken);
  }
  return clientToken;
}

/**
 * Sends a message to the backend chat API.
 * @param {string} message - The user's message.
 * @param {string} clientToken - Optional client token.
 * @returns {Promise<ChatResponse>} The bot's response.
 */
export async function sendChatMessage(message: string, clientToken?: string): Promise<ChatResponse> {
  const token = clientToken || getClientToken();
  const payload: ChatPayload = {
    message,
    client_token: token,
  };

  try {
    const { data } = await api.post<ChatResponse>('/chat/send', payload);
    
    // Save the client token if returned
    if (data.client_token) {
      localStorage.setItem("chat_client_token", data.client_token);
    }
    
    // Save conversation ID
    if (data.conversation_id) {
      localStorage.setItem("chat_conversation_id", data.conversation_id.toString());
    }
    
    return data;
  } catch (error) {
    console.error('Error sending chat message:', error);
    throw error;
  }
}

/**
 * Fetches the conversation from the backend.
 * @param {number} conversationId - The conversation ID.
 * @param {string} clientToken - The client token.
 * @returns {Promise<ConversationResponse>} The conversation data.
 */
export async function getConversation(conversationId: number, clientToken?: string): Promise<ConversationResponse> {
  const token = clientToken || getClientToken();
  
  try {
    const { data } = await api.get<ConversationResponse>('/chat/conversation', {
      params: { 
        conversation_id: conversationId,
        client_token: token 
      },
    });
    return data;
  } catch (error) {
    console.error('Error getting conversation:', error);
    throw error;
  }
}

/**
 * Fetches new messages since last message ID.
 * @param {number} conversationId - The conversation ID.
 * @param {number} lastMessageId - The last message ID.
 * @param {string} clientToken - The client token.
 * @returns {Promise<NewMessagesResponse>} The new messages.
 */
export async function getNewMessages(
  conversationId: number, 
  lastMessageId: number, 
  clientToken?: string
): Promise<NewMessagesResponse> {
  const token = clientToken || getClientToken();
  
  try {
    const { data } = await api.get<NewMessagesResponse>('/chat/messages/new', {
      params: { 
        conversation_id: conversationId,
        last_message_id: lastMessageId,
        client_token: token 
      },
    });
    return data;
  } catch (error) {
    console.error('Error getting new messages:', error);
    throw error;
  }
}

/**
 * Fetches the chat history from the backend.
 * @returns {Promise<ConversationResponse>} The chat history data.
 */
export async function getChatHistory(): Promise<ConversationResponse | null> {
  const clientToken = getClientToken();
  const conversationId = localStorage.getItem("chat_conversation_id");
  
  if (!conversationId) {
    return null;
  }
  
  try {
    return await getConversation(parseInt(conversationId), clientToken);
  } catch (error) {
    console.error('Error getting chat history:', error);
    return null;
  }
}
