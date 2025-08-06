import React from "react";
import { Avatar, Badge, Tooltip } from "antd";
import { UserOutlined } from "@ant-design/icons";

interface UserAvatarProps {
  name?: string;
  src?: string;
  size?: number | "small" | "default" | "large";
  status?: "online" | "offline" | "away" | "busy";
  className?: string;
  style?: React.CSSProperties;
  showTooltip?: boolean;
  shape?: "circle" | "square";
}

const UserAvatar: React.FC<UserAvatarProps> = ({
  name,
  src,
  size = "default",
  status,
  className = "",
  style = {},
  showTooltip = true,
  shape = "circle",
}) => {
  // Generate initials from name
  const getInitials = () => {
    if (!name) return "";

    const nameParts = name.split(" ");
    if (nameParts.length === 1) {
      return nameParts[0].charAt(0).toUpperCase();
    }

    return (
      nameParts[0].charAt(0).toUpperCase() +
      nameParts[nameParts.length - 1].charAt(0).toUpperCase()
    );
  };

  // Determine badge status color
  const getBadgeColor = () => {
    switch (status) {
      case "online":
        return "#52c41a";
      case "offline":
        return "#f5222d";
      case "away":
        return "#faad14";
      case "busy":
        return "#ff7875";
      default:
        return "#d9d9d9";
    }
  };

  const avatar = (
    <Avatar
      src={src}
      size={size}
      className={`font-bevietnam ${className}`}
      style={style}
      shape={shape}
      icon={!src && !name ? <UserOutlined /> : null}
    >
      {!src && name ? getInitials() : null}
    </Avatar>
  );

  const wrappedAvatar = status ? (
    <Badge dot status="processing" color={getBadgeColor()} offset={[-4, 4]}>
      {avatar}
    </Badge>
  ) : (
    avatar
  );

  return showTooltip && name ? (
    <Tooltip title={name} placement="top">
      {wrappedAvatar}
    </Tooltip>
  ) : (
    wrappedAvatar
  );
};

export default UserAvatar;
