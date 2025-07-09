/**
 * Kiểm tra nhanh Google OAuth configuration
 * Chạy trong browser console để debug
 */

// Copy đoạn code này vào browser console để debug Google OAuth
console.log(`
🔍 GOOGLE OAUTH QUICK DEBUG CHECKLIST:

1. ✅ Client ID: ${import.meta.env.VITE_GOOGLE_CLIENT_ID || "❌ MISSING"}

2. ✅ Current Origin: ${window.location.origin}

3. ✅ HTTPS Status: ${
  window.location.protocol === "https:"
    ? "✅ HTTPS"
    : window.location.hostname === "localhost"
    ? "✅ Localhost"
    : "❌ Cần HTTPS"
}

4. ✅ Iframe Status: ${
  window.self === window.top ? "✅ Not in iframe" : "❌ Running in iframe"
}

🚨 QUAN TRỌNG: Trong Google Cloud Console:
   → APIs & Services → Credentials
   → OAuth 2.0 Client IDs → Edit
   → Authorized JavaScript origins phải chứa:
   • ${window.location.origin}
   • http://localhost:3000
   • http://localhost:5173

🔗 Link nhanh: https://console.cloud.google.com/apis/credentials

Nếu vẫn lỗi "Popup window closed":
1. Kiểm tra popup blocker
2. Đảm bảo domain được authorize
3. Thử hard refresh (Ctrl+F5)
4. Check Network tab để xem request nào fail
`);

// Test Google API connectivity
fetch("https://www.googleapis.com/discovery/v1/apis/oauth2/v2/rest")
  .then((r) => r.json())
  .then((d) => console.log("✅ Google API reachable:", d.name))
  .catch((e) => console.error("❌ Google API error:", e));
