import React from "react";
import { Typography, Divider } from "antd";

const { Title, Paragraph } = Typography;

interface SectionHeaderProps {
  title: string;
  subtitle?: string;
  centered?: boolean;
  withDivider?: boolean;
  className?: string;
}

const SectionHeader: React.FC<SectionHeaderProps> = ({
  title,
  subtitle,
  centered = false,
  withDivider = false,
  className = "",
}) => {
  return (
    <div className={`mb-8 ${centered ? "text-center" : ""} ${className}`}>
      <Title level={2} className="font-bevietnam font-bold  mb-2">
        {title}
      </Title>

      {subtitle && (
        <Paragraph className="font-bevietnam  text-lg max-w-2xl mx-auto">
          {subtitle}
        </Paragraph>
      )}

      {withDivider && (
        <div className={`mt-4 ${centered ? "flex justify-center" : ""}`}>
          <Divider
            className={`border-blue-500 border-t-2 ${
              centered ? "w-24" : "w-16"
            } m-0`}
          />
        </div>
      )}
    </div>
  );
};

export default SectionHeader;
