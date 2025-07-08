/**
 * LavishStay Hotel - CPay Payment Verification API
 * Google Apps Script for checking bank transactions
 */

function doGet(e) {
  return doPost(e);
}

function doPost(e) {
  try {
    // Enable CORS
    const response = {
      headers: {
        "Access-Control-Allow-Origin": "*",
        "Access-Control-Allow-Methods": "GET, POST, OPTIONS",
        "Access-Control-Allow-Headers": "Content-Type, Authorization",
        "Content-Type": "application/json",
      },
    };

    // Get parameters from query string or POST body
    let params = {};

    if (e.queryString) {
      // Parse query parameters
      const queryParams = new URLSearchParams(e.queryString);
      for (let [key, value] of queryParams) {
        params[key] = value;
      }
    }

    if (e.postData && e.postData.contents) {
      try {
        const postParams = JSON.parse(e.postData.contents);
        params = { ...params, ...postParams };
      } catch (parseError) {
        // If JSON parsing fails, continue with query params
      }
    }

    // Log request for debugging
    console.log("CPay API Request:", params);

    const action = params.action || "checkPayment";
    const bookingCode = params.booking_code;
    const expectedAmount = parseFloat(params.amount || 0);

    if (!bookingCode) {
      return ContentService.createTextOutput(
        JSON.stringify({
          status: "error",
          message: "Missing booking_code parameter",
        })
      ).setMimeType(ContentService.MimeType.JSON);
    }

    // Get the active spreadsheet
    const sheet = SpreadsheetApp.getActiveSheet();
    const data = sheet.getDataRange().getValues();

    // Skip header row
    const transactions = data.slice(1);

    // Search for matching transaction
    let foundTransaction = null;

    for (let i = 0; i < transactions.length; i++) {
      const row = transactions[i];

      // Extract data from columns (adjust indices based on your sheet structure)
      const date = row[0]; // Column A
      const bank = row[1]; // Column B
      const account = row[2]; // Column C
      const description = row[5] || ""; // Column F
      const type = row[6]; // Column G
      const amount = parseFloat(row[7] || 0); // Column H
      const reference = row[8]; // Column I

      // Check if this is an incoming payment (Tiền vào)
      if (type !== "Tiền vào") continue;

      // Check if description contains booking code
      const descriptionLower = description.toLowerCase();
      const searchPattern = bookingCode.toLowerCase();

      if (descriptionLower.includes(searchPattern)) {
        // Check amount match (allow 1000 VND tolerance)
        const amountMatch = Math.abs(amount - expectedAmount) <= 1000;

        if (amountMatch || expectedAmount === 0) {
          // Allow 0 for testing
          foundTransaction = {
            date: date,
            bank: bank,
            account: account,
            description: description,
            type: type,
            amount: amount,
            reference_code: reference,
            booking_code: bookingCode,
            matched_pattern: searchPattern,
            amount_difference: Math.abs(amount - expectedAmount),
          };
          break;
        }
      }
    }

    if (foundTransaction) {
      console.log("Payment found:", foundTransaction);

      return ContentService.createTextOutput(
        JSON.stringify({
          status: "success",
          message: "Payment found",
          data: [foundTransaction],
          total_transactions: transactions.length,
          search_criteria: {
            booking_code: bookingCode,
            expected_amount: expectedAmount,
          },
        })
      ).setMimeType(ContentService.MimeType.JSON);
    } else {
      console.log("Payment not found for booking:", bookingCode);

      // Return recent transactions for debugging
      const recentTransactions = transactions.slice(-5).map((row) => ({
        date: row[0],
        bank: row[1],
        description: row[5] || "",
        type: row[6],
        amount: parseFloat(row[7] || 0),
        reference: row[8],
      }));

      return ContentService.createTextOutput(
        JSON.stringify({
          status: "error",
          message: "Payment not found",
          data: [],
          debug_info: {
            search_criteria: {
              booking_code: bookingCode,
              expected_amount: expectedAmount,
            },
            total_transactions: transactions.length,
            recent_transactions: recentTransactions,
          },
        })
      ).setMimeType(ContentService.MimeType.JSON);
    }
  } catch (error) {
    console.error("CPay API Error:", error);

    return ContentService.createTextOutput(
      JSON.stringify({
        status: "error",
        message: "Internal server error",
        error: error.toString(),
      })
    ).setMimeType(ContentService.MimeType.JSON);
  }
}

// Test function for development
function testAPI() {
  const testParams = {
    action: "checkPayment",
    booking_code: "LVS63162115",
    amount: 11000,
  };

  const result = doPost({
    queryString: new URLSearchParams(testParams).toString(),
    postData: null,
  });

  console.log("Test result:", result.getContent());
}
