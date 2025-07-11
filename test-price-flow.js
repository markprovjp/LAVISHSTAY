// Test script để kiểm tra flow giá từ SearchResults -> Payment
console.log("🧪 Testing price flow...");

// Simulate data từ SearchResults
const searchResultsPrice = {
  pricePerNight: 11000,
  totalPrice: 198000, // 11000 * 18 nights
  nights: 18,
};

console.log("📊 SearchResults Data:", searchResultsPrice);

// Simulate việc dispatch initializeBookingSelection trong SearchResults
const enhancedPackageOption = {
  id: "standard-package",
  name: "Standard Package",
  pricePerNight: { vnd: searchResultsPrice.pricePerNight },
  calculatedTotalPrice: searchResultsPrice.totalPrice,
  calculatedPricePerNight: searchResultsPrice.pricePerNight,
  nights: searchResultsPrice.nights,
};

console.log(
  "📋 Enhanced Package Option (sent to Redux):",
  enhancedPackageOption
);

// Simulate calculateTotals function
function simulateCalculateTotals(option, quantity, nights) {
  let totalPricePerRoom = 0;

  if (option.calculatedTotalPrice) {
    // Use the pre-calculated total price from SearchResults
    totalPricePerRoom = option.calculatedTotalPrice;
    console.log(`💰 Using calculatedTotalPrice: ${totalPricePerRoom}`);
  } else {
    // Fall back to traditional calculation
    const pricePerNight = option.pricePerNight.vnd;
    totalPricePerRoom = pricePerNight * nights;
    console.log(
      `💰 Using calculated price: ${pricePerNight} x ${nights} = ${totalPricePerRoom}`
    );
  }

  const roomsTotal = totalPricePerRoom * quantity;

  return {
    roomsTotal,
    breakfastTotal: 0,
    serviceFee: 0,
    taxAmount: 0,
    discountAmount: 0,
    finalTotal: roomsTotal,
    nights: nights,
  };
}

// Simulate Redux store calculation
const reduxTotals = simulateCalculateTotals(
  enhancedPackageOption,
  1,
  searchResultsPrice.nights
);
console.log("🏪 Redux Store Totals:", reduxTotals);

// Simulate selectedRoomsSummary selector
function simulateSelectedRoomsSummary(option, reduxTotals) {
  let totalPricePerRoom = 0;
  let pricePerNight = 0;

  if (option.calculatedTotalPrice) {
    totalPricePerRoom = option.calculatedTotalPrice;
    pricePerNight = option.calculatedPricePerNight || 0;
    console.log(
      `📋 Selector using calculatedTotalPrice: ${totalPricePerRoom}, pricePerNight: ${pricePerNight}`
    );
  } else {
    pricePerNight = option.pricePerNight.vnd;
    totalPricePerRoom = pricePerNight * reduxTotals.nights;
    console.log(
      `📋 Selector using calculated price: ${pricePerNight} x ${reduxTotals.nights} = ${totalPricePerRoom}`
    );
  }

  return [
    {
      roomId: "room-1",
      optionId: "standard-package",
      quantity: 1,
      room: { id: 1, name: "Deluxe Room" },
      option: option,
      pricePerNight: pricePerNight,
      totalPrice: totalPricePerRoom,
      key: "room-1-standard-package-0",
    },
  ];
}

const selectedRoomsSummary = simulateSelectedRoomsSummary(
  enhancedPackageOption,
  reduxTotals
);
console.log("📋 Selected Rooms Summary:", selectedRoomsSummary);

// Simulate Payment page totals calculation (OLD WAY - INCORRECT)
function simulateOldPaymentTotals(selectedRoomsSummary, nights) {
  if (selectedRoomsSummary && selectedRoomsSummary.length > 0) {
    const roomsTotal = selectedRoomsSummary.reduce(
      (sum, room) => sum + room.totalPrice,
      0
    );
    return {
      roomsTotal,
      breakfastTotal: 0,
      serviceFee: 0,
      taxAmount: 0,
      discountAmount: 0,
      finalTotal: roomsTotal,
      nights: nights,
    };
  }
  return reduxTotals;
}

// Simulate Payment page totals calculation (NEW WAY - CORRECT)
function simulateNewPaymentTotals(reduxTotals, nights) {
  return {
    ...reduxTotals,
    nights: nights,
  };
}

const oldPaymentTotals = simulateOldPaymentTotals(
  selectedRoomsSummary,
  searchResultsPrice.nights
);
const newPaymentTotals = simulateNewPaymentTotals(
  reduxTotals,
  searchResultsPrice.nights
);

console.log("\n🔍 COMPARISON:");
console.log(
  "SearchResults displayed price:",
  formatVND(searchResultsPrice.totalPrice)
);
console.log("Old Payment calculation:", formatVND(oldPaymentTotals.finalTotal));
console.log("New Payment calculation:", formatVND(newPaymentTotals.finalTotal));

console.log("\n✅ Expected result: All prices should be the same!");
console.log(
  "SearchResults === New Payment:",
  searchResultsPrice.totalPrice === newPaymentTotals.finalTotal
);

function formatVND(amount) {
  return new Intl.NumberFormat("vi-VN", {
    style: "currency",
    currency: "VND",
    minimumFractionDigits: 0,
    maximumFractionDigits: 0,
  }).format(amount);
}
