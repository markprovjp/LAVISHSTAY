import { VietQRConfig } from "./types";

export class VietQRService {
    private static config: VietQRConfig = {
        bankCode: "970415", // VietinBank
        accountNo: "113366668888",
        accountName: "LAVISHSTAY HOTELS VIETNAM",
        template: "compact2"
    };

    static generateQRUrl(amount: number, content: string): string {
        const sanitizedContent = this.sanitizeContent(content);
        
        return `https://img.vietqr.io/image/${this.config.bankCode}-${this.config.accountNo}-${this.config.template}.png?amount=${amount}&addInfo=${encodeURIComponent(sanitizedContent)}&accountName=${encodeURIComponent(this.config.accountName)}`;
    }

    static getConfig(): VietQRConfig {
        return this.config;
    }

    static getBankInfo() {
        return {
            bankName: "VietinBank",
            bankCode: this.config.bankCode,
            accountNo: this.config.accountNo,
            accountName: this.config.accountName
        };
    }

    private static sanitizeContent(content: string): string {
        // Loại bỏ dấu tiếng Việt và ký tự đặc biệt để đảm bảo tương thích
        return content
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '') // Remove Vietnamese accents
            .replace(/[^a-zA-Z0-9\s]/g, '') // Remove special characters
            .trim();
    }

    static generatePaymentContent(bookingId: string): string {
        return `Thanh toan dat phong ${bookingId}`;
    }
}
