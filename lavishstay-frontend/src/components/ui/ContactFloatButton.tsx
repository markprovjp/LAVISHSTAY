import React from 'react';
import { FloatButton, Tooltip } from 'antd';
import { Phone, Mail, MessageCircle, Facebook, Headset } from 'lucide-react';

/**
 * Props for the ContactFloatButton component.
 * This allows for easy configuration of contact links.
 */
interface ContactFloatButtonProps {
  phoneNumber: string;
  facebookUrl: string;
  zaloUrl: string;
  emailAddress: string;
}

/**
 * A floating action button group for displaying contact options.
 * It features a main button that, when clicked, reveals several contact methods.
 *
 * @param {ContactFloatButtonProps} props The contact information for the hotel.
 */
const ContactFloatButton: React.FC<ContactFloatButtonProps> = ({
  phoneNumber,
  facebookUrl,
  zaloUrl,
  emailAddress,
}) => {
  return (
       <FloatButton.Group
      trigger="click"
      type="primary"
      style={{ right: 24, bottom: 164 }}
      icon={<Headset size={20} />}
    >
      <Tooltip title="Gọi ngay cho chúng tôi" placement="left">
        <FloatButton
          icon={<Phone size={20} />}
          href={`tel:${phoneNumber}`}
        />
      </Tooltip>
      <Tooltip title="Gửi Email" placement="left">
        <FloatButton
          icon={<Mail size={20} />}
          href={`mailto:${emailAddress}`}
        />
      </Tooltip>
      <Tooltip title="Nhắn tin qua Zalo" placement="left">
        <FloatButton
          icon={<MessageCircle size={20} />}
          href={zaloUrl}
          target="_blank"
          rel="noopener noreferrer"
        />
      </Tooltip>
      <Tooltip title="Ghé thăm Facebook" placement="left">
        <FloatButton
          icon={<Facebook size={20} />}
          href={facebookUrl}
          target="_blank"
          rel="noopener noreferrer"
        />
      </Tooltip>
    </FloatButton.Group>
  );
};

export default ContactFloatButton;
