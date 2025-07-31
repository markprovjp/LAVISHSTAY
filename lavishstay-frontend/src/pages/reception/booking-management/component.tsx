            {/* Package Comparison Modal */}
            <Modal
                title={
                    <div className="flex items-center gap-3">
                        <GiftOutlined className="text-blue-600" />
                        <span className="text-xl font-semibold text-gray-800">So sánh gói dịch vụ - {selectedRoom?.name}</span>
                    </div>
                }
                open={compareModalVisible}
                onCancel={() => setCompareModalVisible(false)}
                footer={[
                    <Button key="close" size="large" onClick={() => setCompareModalVisible(false)}>
                        Đóng
                    </Button>
                ]}
                width={1200}
                className="package-comparison-modal"
            >
                {selectedRoom && (
                    <div>
                        {/* Package Comparison Table */}
                        <div className="mb-6">
                            <Title level={5} className="mb-4">Tất cả gói dịch vụ có sẵn</Title>
                            <Row gutter={[24, 24]}>
                                {(selectedRoom.options || []).map((option: any, index: number) => {
                                    const isSelected = selectedPackages[selectedRoom.id] === option.id || (!selectedPackages[selectedRoom.id] && index === 0);
                                    return (
                                        <Col key={option.id} xs={24} md={8}>
                                            <Card
                                                className={`h-full transition-all duration-300 cursor-pointer ${isSelected
                                                    ? 'border-blue-500 shadow-lg bg-gradient-to-br from-blue-50 to-white'
                                                    : 'border-gray-200 hover:border-blue-300 hover:shadow-md'
                                                    }`}
                                                onClick={() => handlePackageSelect(selectedRoom.id, option.id)}
                                            >
                                                <div className="relative">
                                                    {/* Selection Badge */}
                                                    {isSelected && (
                                                        <div className="absolute -top-3 -right-3 w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center shadow-lg z-10">
                                                            <CheckOutlined className="text-white text-sm" />
                                                        </div>
                                                    )}

                                                    {/* Package Header */}
                                                    <div className="mb-4">
                                                        <div className="flex items-center justify-between mb-2">
                                                            <div className="flex items-center gap-2">
                                                                {option.recommended && <CrownOutlined className="text-yellow-500" />}
                                                                {option.mostPopular && <ThunderboltOutlined className="text-red-500" />}
                                                                <span className="font-bold text-lg text-gray-900">{option.name}</span>
                                                            </div>
                                                        </div>

                                                        <div className="flex gap-2 mb-3">
                                                            {option.recommended && (
                                                                <Tag color="gold" className="px-3 py-1">
                                                                    <StarOutlined className="mr-1" /> Đề xuất
                                                                </Tag>
                                                            )}
                                                            {option.mostPopular && (
                                                                <Tag color="red" className="px-3 py-1">
                                                                    <FireOutlined className="mr-1" /> Phổ biến
                                                                </Tag>
                                                            )}
                                                        </div>
                                                    </div>

                                                    {/* Price Display */}
                                                    <div className={`text-center p-4 rounded-lg mb-4 ${isSelected ? 'bg-blue-100' : 'bg-gray-100'
                                                        }`}>
                                                        <div className="mb-2">
                                                            <div className={`text-2xl font-bold ${isSelected ? 'text-blue-600' : 'text-gray-800'
                                                                }`}>
                                                                {formatVND(option.totalPrice || 0)}
                                                            </div>
                                                            <div className="text-sm text-gray-600">
                                                                Tổng cho {searchResults?.searchSummary?.nights || 1} đêm
                                                            </div>
                                                        </div>

                                                        <Divider className="my-3" />

                                                        <div className="text-sm space-y-1">
                                                            <div className="flex justify-between">
                                                                <span className="text-gray-600">Giá mỗi đêm:</span>
                                                                <span className="font-semibold">
                                                                    {formatVND(option.pricePerNight?.vnd || 0)}
                                                                </span>
                                                            </div>

                                                            {/* Pricing breakdown from API */}
                                                            {option.pricing && (
                                                                <>
                                                                    <div className="flex justify-between">
                                                                        <span className="text-gray-600">Giá cơ bản:</span>
                                                                        <span>{formatVND(option.pricing.base_price_per_night || 0)}</span>
                                                                    </div>
                                                                    <div className="flex justify-between">
                                                                        <span className="text-gray-600">Phụ phí gói:</span>
                                                                        <span>{formatVND(option.pricing.package_modifier || 0)}</span>
                                                                    </div>
                                                                    <div className="flex justify-between">
                                                                        <span className="text-gray-600">Số đêm:</span>
                                                                        <span>{option.pricing.nights || 1} đêm</span>
                                                                    </div>
                                                                    <div className="flex justify-between">
                                                                        <span className="text-gray-600">Số phòng:</span>
                                                                        <span>{option.pricing.rooms_needed || 1} phòng</span>
                                                                    </div>
                                                                </>
                                                            )}
                                                        </div>
                                                    </div>

                                                    {/* Package Description */}
                                                    <div className="mb-4">
                                                        <Text className="text-gray-600 leading-relaxed text-sm">
                                                            {option.description}
                                                        </Text>
                                                    </div>

                                                    {/* Additional Services */}
                                                    {option.additionalServices && option.additionalServices.length > 0 && (
                                                        <div className="mb-4">
                                                            <Title level={5} className="mb-2 text-sm">Dịch vụ bổ sung</Title>
                                                            <div className="space-y-1">
                                                                {option.additionalServices.map((service: any, idx: number) => (
                                                                    <div key={idx} className="flex items-center text-sm text-gray-600">
                                                                        <CheckOutlined className="text-green-500 mr-2 text-xs" />
                                                                        {service.name || service}
                                                                    </div>
                                                                ))}
                                                            </div>
                                                        </div>
                                                    )}

                                                    {/* Package Features - Using API data instead of hardcoded values */}
                                                    <div className="space-y-2">
                                                        {/* Cancellation Policy */}
                                                        {option.cancellationPolicy && (
                                                            <div className="flex items-center text-sm">
                                                                <CheckOutlined className="text-green-500 mr-2" />
                                                                <span>{option.cancellationPolicy}</span>
                                                                {option.freeCancellationDays && (
                                                                    <span className="text-gray-500 ml-1">
                                                                        (Miễn phí {option.freeCancellationDays} ngày trước check-in)
                                                                    </span>
                                                                )}
                                                            </div>
                                                        )}

                                                        {/* Payment Policy */}
                                                        {option.paymentPolicy && (
                                                            <div className="flex items-center text-sm">
                                                                <CheckOutlined className="text-green-500 mr-2" />
                                                                <span>{option.paymentPolicy}</span>
                                                                {(option.depositPercentage || option.depositFixedAmount) && (
                                                                    <span className="text-gray-500 ml-1">
                                                                        (Đặt cọc: {option.depositPercentage ? `${option.depositPercentage}%` : ''}
                                                                        {option.depositFixedAmount ? `${formatVND(option.depositFixedAmount)}` : ''})
                                                                    </span>
                                                                )}
                                                            </div>
                                                        )}

                                                        {/* Check-out Policy */}
                                                        {option.checkOutPolicy && (
                                                            <div className="flex items-center text-sm">
                                                                <CheckOutlined className="text-green-500 mr-2" />
                                                                <span>{option.checkOutPolicy}</span>
                                                                {option.standardCheckOutTime && (
                                                                    <span className="text-gray-500 ml-1">
                                                                        (Trả phòng: {option.standardCheckOutTime})
                                                                    </span>
                                                                )}
                                                            </div>
                                                        )}

                                                        {/* Always show instant confirmation */}
                                                        <div className="flex items-center text-sm">
                                                            <CheckOutlined className="text-green-500 mr-2" />
                                                            <span>Xác nhận ngay lập tức</span>
                                                        </div>
                                                    </div>

                                                    {/* Select Button */}
                                                    <div className="mt-4">
                                                        <Button
                                                            type={isSelected ? "primary" : "default"}
                                                            block
                                                            size="large"
                                                            className={isSelected ? "gradientButton" : ""}
                                                        >
                                                            {isSelected ? 'Đã chọn' : 'Chọn gói này'}
                                                        </Button>
                                                    </div>
                                                </div>
                                            </Card>
                                        </Col>
                                    );
                                })}
                            </Row>
                        </div>

                        {/* Summary */}
                        <div className="border-t pt-4">
                            <div className="flex items-center justify-between">
                                <div>
                                    <span className="text-gray-600">Gói đã chọn: </span>
                                    <span className="font-semibold">
                                        {selectedRoom.options?.find((opt: any) =>
                                            opt.id === (selectedPackages[selectedRoom.id] || selectedRoom.options?.[0]?.id)
                                        )?.name || 'Chưa chọn'}
                                    </span>
                                </div>
                                <div className="text-right">
                                    <div className="text-lg font-bold text-blue-600">
                                        Tổng: {formatVND(
                                            selectedRoom.options?.find((opt: any) =>
                                                opt.id === (selectedPackages[selectedRoom.id] || selectedRoom.options?.[0]?.id)
                                            )?.totalPrice || 0
                                        )}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                )}
            </Modal>