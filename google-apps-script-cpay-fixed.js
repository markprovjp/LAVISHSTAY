function doGet(e) {
  try {
    // Get parameters from URL
    const action = e.parameter.action;
    const bookingCode = e.parameter.booking_code;
    const amount = parseFloat(e.parameter.amount);

    Logger.log(
      "🔍 CPay API Request: " +
        JSON.stringify({
          action: action,
          booking_code: bookingCode,
          amount: amount,
        })
    );

    if (action === "checkPayment") {
      return checkPayment(bookingCode, amount);
    }

    return ContentService.createTextOutput(
      JSON.stringify({
        status: "error",
        message: "Invalid action",
      })
    ).setMimeType(ContentService.MimeType.JSON);
  } catch (error) {
    Logger.log("💥 Error in doGet: " + error.toString());
    return ContentService.createTextOutput(
      JSON.stringify({
        status: "error",
        message: "Internal server error",
        error: error.toString(),
      })
    ).setMimeType(ContentService.MimeType.JSON);
  }
}

function doPost(e) {
  try {
    // Parse POST data
    let postData = {};

    if (e.postData && e.postData.contents) {
      try {
        postData = JSON.parse(e.postData.contents);
      } catch (parseError) {
        Logger.log("⚠️ Failed to parse POST data as JSON, using parameters");
        postData = e.parameter || {};
      }
    } else {
      postData = e.parameter || {};
    }

    const action = postData.action;
    const bookingCode = postData.booking_code;
    const amount = parseFloat(postData.amount);

    Logger.log(
      "🔍 CPay API POST Request: " +
        JSON.stringify({
          action: action,
          booking_code: bookingCode,
          amount: amount,
        })
    );

    if (action === "checkPayment") {
      return checkPayment(bookingCode, amount);
    }

    return ContentService.createTextOutput(
      JSON.stringify({
        status: "error",
        message: "Invalid action",
      })
    ).setMimeType(ContentService.MimeType.JSON);
  } catch (error) {
    Logger.log("💥 Error in doPost: " + error.toString());
    return ContentService.createTextOutput(
      JSON.stringify({
        status: "error",
        message: "Internal server error",
        error: error.toString(),
      })
    ).setMimeType(ContentService.MimeType.JSON);
  }
}

function checkPayment(bookingCode, expectedAmount) {
  try {
    Logger.log(
      `🔍 Checking payment for booking: ${bookingCode}, amount: ${expectedAmount}`
    );

    // Open the Google Sheet (thay bằng ID sheet của bạn)
    const SHEET_ID = "1NcE0gzaNR9rpXqxJG-YAoiTBjyawJwPaM_EF363GwAY"; // Sheet ID thực
    const sheet = SpreadsheetApp.openById(SHEET_ID).getActiveSheet();

    // Get all data from sheet
    const data = sheet.getDataRange().getValues();

    // Remove header row
    const rows = data.slice(1);

    Logger.log(`📊 Found ${rows.length} transactions in sheet`);

    // Search for matching transaction
    for (let i = 0; i < rows.length; i++) {
      const row = rows[i];

      // Column mapping based on your sheet structure:
      // A: Date, B: Bank, C: Account, D: Empty, E: Empty, F: Description, G: Type, H: Amount, I: Reference, J: Balance
      const date = row[0];
      const bank = row[1];
      const account = row[2];
      const description = (row[5] || "").toString().toLowerCase();
      const type = row[6];
      const amount = parseFloat(row[7]) || 0;
      const reference = row[8];

      // Look for booking code in description
      const searchPattern = bookingCode.toLowerCase();

      Logger.log(
        `🔍 Checking row ${
          i + 1
        }: "${description}" for pattern "${searchPattern}"`
      );

      if (description.includes(searchPattern) && type === "Tiền vào") {
        // Check amount match with tolerance
        const amountMatch = Math.abs(amount - expectedAmount) <= 1000; // 1000 VND tolerance

        Logger.log(
          `💰 Amount check: ${amount} vs ${expectedAmount}, match: ${amountMatch}`
        );

        if (amountMatch) {
          Logger.log(`✅ Payment found for booking ${bookingCode}`);

          return ContentService.createTextOutput(
            JSON.stringify({
              status: "success",
              message: "Payment found",
              data: [
                {
                  date: date,
                  bank: bank,
                  account: account,
                  content: description,
                  type: type,
                  amount: amount,
                  reference_code: reference,
                  row_number: i + 2, // +2 because of header and 0-based index
                },
              ],
            })
          ).setMimeType(ContentService.MimeType.JSON);
        }
      }
    }

    Logger.log(`❌ No payment found for booking ${bookingCode}`);

    return ContentService.createTextOutput(
      JSON.stringify({
        status: "success",
        message: "No payment found",
        data: [],
        debug_info: {
          total_rows: rows.length,
          search_pattern: bookingCode,
          expected_amount: expectedAmount,
        },
      })
    ).setMimeType(ContentService.MimeType.JSON);
  } catch (error) {
    Logger.log("💥 Error in checkPayment: " + error.toString());
    return ContentService.createTextOutput(
      JSON.stringify({
        status: "error",
        message: "Error checking payment",
        error: error.toString(),
      })
    ).setMimeType(ContentService.MimeType.JSON);
  }
}

// Test function để kiểm tra trong Apps Script editor
function testCheckPayment() {
  const result = checkPayment("LVS63162115", 11000);
  Logger.log("Test result: " + result.getContent());
}
