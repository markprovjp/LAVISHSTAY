import React from "react";
import { Card, Typography } from "antd";
import { CheckCircleOutlined } from "@ant-design/icons";

const { Title, Paragraph } = Typography;

interface FeatureCardProps {
  icon: React.ReactNode;
  title: string;
  description: string;
  className?: string;
  benefits?: string[];
  highlighted?: boolean;
}

const FeatureCard: React.FC<FeatureCardProps> = ({
  icon,
  title,
  description,
  className = "",
  benefits = [],
  highlighted = false,
}) => {
  return (
    <Card
      className={`h-full feature-card transition-all duration-300 hover:shadow-lg 
        ${highlighted ? "border-blue-500 shadow-md" : ""} ${className}`}
      styles={{ body: { padding: "24px" } }}
    >
      <div className="flex flex-col items-center text-center">
        <div className={`text-4xl mb-4 ${highlighted ? "text-blue-500" : ""}`}>
          {icon}
        </div>

        <Title
          level={4}
          className={`font-bevietnam font-semibold mb-3
            ${highlighted ? "text-blue-600" : ""}`}
        >
          {title}
        </Title>

        <Paragraph className="font-bevietnam  mb-4">{description}</Paragraph>

        {benefits.length > 0 && (
          <ul className="text-left w-full pl-0 mt-2">
            {benefits.map((benefit, index) => (
              <li key={index} className="flex items-start mb-2">
                <CheckCircleOutlined className="text-green-500 mt-1 mr-2" />
                <span className="font-bevietnam ">{benefit}</span>
              </li>
            ))}
          </ul>
        )}
      </div>
    </Card>
  );
};

export default FeatureCard;
