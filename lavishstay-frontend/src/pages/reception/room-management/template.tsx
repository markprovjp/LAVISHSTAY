const handleConfirmAndPay = () => {
    const selectedRoomsList = masterRoomList.filter((room: any) => selectedRoomIds.has(room.id));

    // Validate số lượng khách từng phòng
    for (const room of selectedRoomsList) {
        const details = guestDetails[room.id] || { adults: 1, children: 0, childrenAges: [] };
        const total = details.adults + details.children;
        if (details.adults > 2 || details.children > 4 || total > 6) {
            message.error(`Phòng ${room.name} vượt quá số lượng khách cho phép (tối đa 2 người lớn, 4 trẻ em, tổng 6).`);
            return;
        }
    }

    const roomsWithGuests = selectedRoomsList.map((room: any) => {
        const guestConfig = guestDetails[room.id] || { adults: 1, children: 0, childrenAges: [] };
        const roomTypeId = room.room_type.id.toString();
        const packageId = selectedPackages[roomTypeId];
        let option_id = null, option_name = null, room_price = 0;

        if (availableRoomsData) {
            const roomTypeData = availableRoomsData.data.find((rt: any) => rt.room_type_id.toString() === roomTypeId);
            if (roomTypeData) {
                const pkg = roomTypeData.package_options.find((p: any) => p.package_id === packageId);
                if (pkg) {
                    option_id = pkg.package_id;
                    option_name = pkg.package_name;
                    room_price = pkg.price_per_room_per_night;
                }
            }
        }

        return {
            room_id: room.id,
            package_id: packageId,
            option_id,
            option_name,
            room_price,
            adults: guestConfig.adults,
            children: guestConfig.childrenAges.map(age => ({ age })),
            children_age: guestConfig.childrenAges,
        };
    });

    const bookingData = {
        checkInDate: currentFilters.dateRange![0],
        checkOutDate: currentFilters.dateRange![1],
        guestRooms: selectedRoomsList.map(room => {
            const details = guestDetails[room.id] || { adults: 1, children: 0, childrenAges: [] };
            return {
                adults: details.adults,
                children: details.childrenAges.map(age => ({ age })),
            };
        }),
        selectedPackages,
        availableRoomsData: availableRoomsData,
        roomsWithGuests,
    };

    navigate('/reception/confirm-representative-payment', {
        state: {
            selectedRooms: selectedRoomsList,
            bookingData,
        }
    });
};