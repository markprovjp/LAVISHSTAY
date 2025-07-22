import { GoogleGenerativeAI, Content, Part, GenerationConfig } from "@google/generative-ai";
import api from '../utils/api'; // Import the existing axios instance
import dayjs from 'dayjs';

// --- Type Definitions ---
export type ChatRole = "user" | "model";
export interface ChatMessage { role: ChatRole; parts: Part[]; }
interface ExtractedParams {
    check_in_date?: string;
    check_out_date?: string;
    adults?: number;
    children?: number;
}

// --- API Initialization ---
const API_KEY = import.meta.env.VITE_GEMINI_API_KEY;
if (!API_KEY) throw new Error("VITE_GEMINI_API_KEY is not defined in the .env file");
const genAI = new GoogleGenerativeAI(API_KEY);
const generationConfig: GenerationConfig = { temperature: 0.2, topP: 1, maxOutputTokens: 2048 };
const model = genAI.getGenerativeModel({ model: "gemini-1.5-flash", generationConfig });

// --- Helper Functions ---
const cleanMarkdown = (text: string): string => text.replace(/```json|```/g, '').trim();

// =================================================================
// STEP 1: EXTRACT PARAMETERS FROM USER'S MESSAGE
// =================================================================
async function extractBookingParams(message: string): Promise<ExtractedParams | null> {
    const today = dayjs().format('YYYY-MM-DD');
    const prompt = `
        You are a parameter extraction tool. Analyze the user's message and extract the following information in JSON format.
        - check_in_date (YYYY-MM-DD)
        - check_out_date (YYYY-MM-DD)
        - adults (number)
        - children (number)
        
        Rules:
        - Today's date is ${today}.
        - "ngày mai" means tomorrow. "tuần sau" means 7 days from today.
        - If check-in is specified but check-out is not, assume a 1-night stay.
        - If no dates are mentioned, leave the date fields null.
        - If the user mentions "tôi", "một mình", assume 1 adult. If they mention "chúng tôi", "hai người", assume 2 adults.
        - Only return the JSON object, nothing else.

        User message: "${message}"
    `;
    try {
        const result = await model.generateContent(prompt);
        const text = result.response.text();
        const cleanedJson = cleanMarkdown(text);
        return JSON.parse(cleanedJson) as ExtractedParams;
    } catch (error) {
        console.error("Failed to extract parameters:", error);
        return null;
    }
}

// =================================================================
// STEP 2: FETCH REAL-TIME DATA BASED ON EXTRACTED PARAMETERS
// =================================================================
async function getLiveAvailabilityContext(params: ExtractedParams): Promise<string> {
    // Use extracted params, or default to today/tomorrow if none were found
    const check_in_date = params.check_in_date || dayjs().format('YYYY-MM-DD');
    const check_out_date = params.check_out_date || dayjs(check_in_date).add(1, 'day').format('YYYY-MM-DD');

    try {
        const response = await api.get('/rooms/available', {
            params: { check_in_date, check_out_date }
        });
        const roomTypes = response.data?.data || [];

        if (roomTypes.length === 0) {
            return `Rất tiếc, không có phòng nào còn trống cho khoảng thời gian từ ${dayjs(check_in_date).format('DD/MM')} đến ${dayjs(check_out_date).format('DD/MM')}.`;
        }

        const liveDataString = roomTypes.map((rt: any) => {
            const availableCount = rt.available_rooms?.length || 0;
            if (availableCount === 0) return null;
            return `- ${rt.name}: còn ${availableCount} phòng, giá ${new Intl.NumberFormat('vi-VN').format(rt.base_price)} VND/đêm.`;
        }).filter(Boolean).join('\n');

        return `Dưới đây là dữ liệu phòng trống và giá chính xác cho khoảng thời gian từ ${dayjs(check_in_date).format('DD/MM/YYYY')} đến ${dayjs(check_out_date).format('DD/MM/YYYY')}:\n${liveDataString}`;
    } catch (error) {
        console.error("Failed to fetch live availability data:", error);
        return "Xin lỗi, tôi không thể kiểm tra tình trạng phòng trống ngay lúc này do lỗi hệ thống.";
    }
}

// =================================================================
// FINAL STEP: GENERATE AUGMENTED RESPONSE
// =================================================================
export async function sendChatMessage(history: Content[], newMessage: string): Promise<string> {
    try {
        // First, try to extract structured data from the user's message
        const extractedParams = await extractBookingParams(newMessage);
        let context = "";

        // If we successfully extracted parameters, fetch live data
        if (extractedParams && (extractedParams.check_in_date || extractedParams.adults)) {
            console.log("Parameters extracted, fetching live data:", extractedParams);
            context = await getLiveAvailabilityContext(extractedParams);
        } else {
            // Fallback for general questions
            console.log("No specific parameters found, treating as a general query.");
            context = "Người dùng đang hỏi một câu hỏi chung. Hãy trả lời dựa trên kiến thức của bạn về khách sạn.";
        }

        // Now, build the final prompt for the AI to generate a user-friendly response
        const finalPrompt = `
            You are "Lavish", a friendly and professional AI concierge for the LavishStay Hotel.
            Your task is to answer the user's question based *only* on the provided context below. Do not use any other knowledge.
            Answer in plain text, without markdown.

            **Context:**
            ${context}

            **User's Question:**
            "${newMessage}"
        `;

        const result = await model.generateContent(finalPrompt);
        return result.response.text();

    } catch (error) {
        console.error("Error in sendChatMessage:", error);
        return "Rất tiếc, đã có lỗi xảy ra khi kết nối với trợ lý AI. Vui lòng thử lại sau.";
    }
}