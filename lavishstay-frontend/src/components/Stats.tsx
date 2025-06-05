import React from "react";
import { Row, Col, Card, Typography, Statistic } from "antd";
import CountUp from "react-countup";

const { Title } = Typography;

interface StatItem {
  title: string;
  value: number;
  suffix?: string;
  prefix?: string;
  icon?: React.ReactNode;
}

interface StatsProps {
  title?: string;
  subtitle?: string;
  stats: StatItem[];
  className?: string;
}

const formatter = (value: number) => (
  <CountUp end={value} separator="," duration={5.5} />
);

const Stats: React.FC<StatsProps> = ({
  title,
  subtitle,
  stats = [],
  className = "",
}) => {
  return (
    <div className={`${className}`}>
      {(title || subtitle) && (
        <div className="text-center mb-10">
          {title && (
            <Title level={2} className="font-bevietnam font-bold ">
              {title}
            </Title>
          )}
          {subtitle && (
            <p className="font-bevietnam  text-lg max-w-2xl mx-auto">
              {subtitle}
            </p>
          )}
        </div>
      )}

      <Row gutter={[24, 24]}>
        {stats.map((stat, index) => (
          <Col xs={24} sm={12} md={stats.length === 4 ? 6 : 8} key={index}>
            <Card
              className="text-center h-full hover:shadow-md transition-shadow duration-300"
              variant="borderless"
            >
              {stat.icon && (
                <div className="text-4xl text-blue-500 mb-4">{stat.icon}</div>
              )}
              <Statistic
                title={
                  <span className="font-bevietnam  text-base">
                    {stat.title}
                  </span>
                }
                value={stat.value}
                formatter={formatter}
                prefix={stat.prefix}
                suffix={stat.suffix}
                valueStyle={{
                  color: "#1890ff",
                  fontWeight: "bold",
                  fontSize: "28px",
                  fontFamily: "'Be Vietnam Pro', 'Open Sans', sans-serif",
                }}
              />
            </Card>
          </Col>
        ))}
      </Row>
    </div>
  );
};

export default Stats;
