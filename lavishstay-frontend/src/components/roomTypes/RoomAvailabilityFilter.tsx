import React, { useState } from "react";
import { Card, DatePicker, InputNumber, Button, Space, Typography } from "antd";
import {
    CalendarOutlined,
    UserOutlined,
    SearchOutlined,
} from "@ant-design/icons";
import dayjs, { Dayjs } from "dayjs";

const { Title } = Typography;
const { RangePicker } = DatePicker;

interface RoomAvailabilityFilterProps {
    maxGuests: number;
    onSearch: (
        dates: [Dayjs | null, Dayjs | null] | null,
        guests: number
    ) => void;
}

const RoomAvailabilityFilter: React.FC<RoomAvailabilityFilterProps> = ({
    maxGuests,
    onSearch,
}) => {
    const [dates, setDates] = useState<[Dayjs | null, Dayjs | null] | null>(null);
    const [guests, setGuests] = useState<number>(1);

    const disabledDate = (current: Dayjs) => {
        return current && current < dayjs().startOf("day");
    };

    const handleSearch = () => {
        onSearch(dates, guests);
    };

    return (
        <Card className="mb-6 shadow-sm border-0">
            <Title level={4} className="mb-4">
                Kiểm tra tình trạng phòng
            </Title>

            <Space direction="vertical" size="large" className="w-full">
                {/* Date Range Picker */}
                <div>
                    <label className="block text-sm font-medium mb-2">
                        Ngày nhận phòng - Ngày trả phòng
                    </label>
                    <RangePicker
                        className="w-full"
                        size="large"
                        placeholder={["Ngày nhận phòng", "Ngày trả phòng"]}
                        suffixIcon={<CalendarOutlined />}
                        disabledDate={disabledDate}
                        onChange={(dates) =>
                            setDates(dates as [Dayjs | null, Dayjs | null])
                        }
                        format="DD/MM/YYYY"
                    />
                </div>

                {/* Guest Count */}
                <div>
                    <label className="block text-sm font-medium mb-2">
                        Số khách (Tối đa {maxGuests} người)
                    </label>
                    <InputNumber
                        className="w-full"
                        size="large"
                        min={1}
                        max={maxGuests}
                        value={guests}
                        prefix={<UserOutlined />}
                        onChange={(value) => setGuests(value || 1)}
                        disabled={maxGuests <= 2}
                    />
                </div>

                {/* Search Button */}
                <Button
                    type="primary"
                    size="large"
                    icon={<SearchOutlined />}
                    onClick={handleSearch}
                    className="w-full bg-blue-600 "
                    disabled={!dates || !dates[0] || !dates[1]}
                >
                    Kiểm tra tình trạng
                </Button>
            </Space>
        </Card>
    );
};

export default RoomAvailabilityFilter;
