import api from '../utils/api';

// --- Type Definitions for API communication ---

/**
 * Payload sent to the chat API.
 */
interface ChatPayload {
  message: string;
  client_token: string;
}

/**
 * Expected response from the chat API.
 */
interface ChatResponse {
  reply: string;
  // Add other potential fields from the response here
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
 * @returns {Promise<ChatResponse>} The bot's response.
 */
export async function sendChatMessage(message: string): Promise<ChatResponse> {
  const clientToken = getClientToken();
  const payload: ChatPayload = {
    message,
    client_token: clientToken,
  };

  // The axios instance in 'api' should already handle the Authorization header
  // with the user's token if they are logged in, so we don't need to pass it manually.
  const { data } = await api.post<ChatResponse>('/chat/send', payload);
  return data;
}

/**
 * Fetches the chat history from the backend.
 * @returns {Promise<any>} The chat history data.
 */
export async function getChatHistory(): Promise<any> {
    const clientToken = getClientToken();
    const { data } = await api.get('/chat/history', {
        params: { client_token: clientToken },
    });
    return data;
}