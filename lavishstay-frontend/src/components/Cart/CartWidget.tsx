// src/components/Cart/CartWidget.tsx
import React, { useState, useCallback } from "react";
import {
  ShoppingCartOutlined,
  DeleteOutlined,
  PlusOutlined,
  MinusOutlined,
} from "@ant-design/icons";
import {
  Badge,
  Button,
  Drawer,
  List,
  Typography,
  Space,
  InputNumber,
  Empty,
  Divider,
  Avatar,
} from "antd";
import useCartStore from "../../store/useCartStore";

const { Text } = Typography;

const CartWidget: React.FC = React.memo(() => {
  const [open, setOpen] = useState(false);
  const {
    items,
    totalItems,
    totalPrice,
    updateQuantity,
    removeItem,
    clearCart,
  } = useCartStore();

  // Memoize event handlers
  const showDrawer = useCallback(() => {
    setOpen(true);
  }, []);

  const onClose = useCallback(() => {
    setOpen(false);
  }, []);
  const formatPrice = useCallback((price: number) => {
    return price.toFixed(2);
  }, []);

  // Memoize list actions to prevent re-renders
  const getListActions = useCallback((item: any) => [
    <Button
      key="delete"
      type="text"
      danger
      icon={<DeleteOutlined />}
      onClick={() => removeItem(item.id)}
    />,
  ], [removeItem]);

  // Memoize quantity controls
  const getQuantityControls = useCallback((item: any) => (
    <div className="flex items-center">
      <Button
        type="text"
        icon={<MinusOutlined />}
        onClick={() => updateQuantity(item.id, item.quantity - 1)}
        disabled={item.quantity <= 1}
      />
      <InputNumber
        min={1}
        max={99}
        value={item.quantity}
        onChange={(value) =>
          value && updateQuantity(item.id, value)
        }
        controls={false}
        className="w-12 mx-1 text-center"
      />
      <Button
        type="text"
        icon={<PlusOutlined />}
        onClick={() => updateQuantity(item.id, item.quantity + 1)}
      />
    </div>
  ), [updateQuantity]);

  return (
    <>
      <Badge count={totalItems} showZero>
        <Button
          type="text"
          icon={<ShoppingCartOutlined style={{ fontSize: "18px" }} />}
          onClick={showDrawer}
          className="flex items-center"
        />
      </Badge>
      <Drawer
        title="Your Cart"
        placement="right"
        onClose={onClose}
        open={open}
        width={420}
        footer={
          <div className="p-1">
            <div className="flex justify-between mb-4">
              <Text strong>Total:</Text>
              <Text strong className="text-xl text-indigo-700">
                ${formatPrice(totalPrice)}
              </Text>
            </div>
            <Space className="w-full">
              <Button danger onClick={clearCart} disabled={items.length === 0}>
                Clear Cart
              </Button>
              <Button
                type="primary"
                block
                disabled={items.length === 0}
                onClick={() => {
                  // Checkout logic would go here
                  alert("Proceeding to checkout!");
                }}
              >
                Checkout
              </Button>
            </Space>
          </div>
        }
      >
        {items.length === 0 ? (
          <Empty
            description="Your cart is empty"
            image={Empty.PRESENTED_IMAGE_SIMPLE}
          />
        ) : (<List
          itemLayout="horizontal"
          dataSource={items}
          renderItem={(item) => (
            <List.Item
              actions={getListActions(item)}
            >
              <List.Item.Meta
                avatar={
                  <Avatar
                    shape="square"
                    size={64}
                    src={
                      item.imageUrl ||
                      `https://placehold.co/64x64?text=${item.name.charAt(0)}`
                    }
                  />
                }
                title={<Text strong>{item.name}</Text>}
                description={
                  <Text type="secondary">${formatPrice(item.price)}</Text>
                }
              />
              {getQuantityControls(item)}
            </List.Item>
          )}
        />
        )}

        {items.length > 0 && (
          <>
            <Divider />
            <div className="space-y-2">
              {items.map((item) => (
                <div key={item.id} className="flex justify-between">
                  <Text>
                    {item.name} (x{item.quantity})
                  </Text>
                  <Text>${formatPrice(item.price * item.quantity)}</Text>
                </div>
              ))}
            </div>
          </>
        )}
      </Drawer>
    </>
  );
});

CartWidget.displayName = 'CartWidget';

export default CartWidget;
