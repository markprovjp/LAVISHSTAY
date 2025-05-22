import React from "react";
import { Button, Dropdown, Menu, Space, Typography } from "antd";
import { GlobalOutlined } from "@ant-design/icons";
import { useTranslation } from "react-i18next";
import { theme } from "antd";
import viFlag from "../../assets/images/vi-flag.svg";
import enFlag from "../../assets/images/en-flag.svg";

const { Text } = Typography;

interface LanguageSwitcherProps {
  mode?: "icon-only" | "with-text";
  className?: string;
}

const LanguageSwitcher: React.FC<LanguageSwitcherProps> = ({
  mode = "icon-only",
  className = "",
}) => {
  const { i18n } = useTranslation();
  const { token } = theme.useToken();

  const currentLanguage = i18n.language;

  const changeLanguage = (lng: string) => {
    i18n.changeLanguage(lng);
  };

  const languages = [
    {
      key: "vi",
      label: "Tiếng Việt",
      icon: viFlag,
      short: "VI",
    },
    {
      key: "en",
      label: "English",
      icon: enFlag,
      short: "EN",
    },
  ];

  const menu = (
    <Menu
      style={{
        borderRadius: token.borderRadius,
        background: token.colorBgContainer,
        boxShadow: token.boxShadowTertiary,
      }}
    >
      {languages.map((lang) => (
        <Menu.Item
          key={lang.key}
          onClick={() => changeLanguage(lang.key)}
          style={{
            backgroundColor:
              currentLanguage === lang.key
                ? `${token.colorPrimary}10`
                : undefined,
          }}
        >
          <div className="flex items-center space-x-2">
            <img
              src={lang.icon}
              alt={lang.label}
              className="w-4 h-4 rounded-full object-cover"
            />
            <Text style={{ color: token.colorTextBase }}>{lang.label}</Text>
          </div>
        </Menu.Item>
      ))}
    </Menu>
  );

  // Find current language object
  const currentLang =
    languages.find((lang) => lang.key === currentLanguage) || languages[0];

  return (
    <Dropdown
      overlay={menu}
      trigger={["click"]}
      placement="bottomRight"
      className={className}
    >
      <Button
        type="text"
        icon={<GlobalOutlined style={{ fontSize: 15 }} />}
        style={{
          color: token.colorTextBase,
          padding: mode === "with-text" ? "0 8px" : undefined,
          borderRadius: "50%",
          display: "flex",
          alignItems: "center",
          justifyContent: "center",
        }}
      >
        {mode === "with-text" && (
          <Space className="ml-1">
            <Text
              style={{
                color: token.colorTextBase,
                fontFamily: token.fontFamily,
              }}
            >
              {currentLang.short}
            </Text>
          </Space>
        )}
      </Button>
    </Dropdown>
  );
};

export default LanguageSwitcher;
