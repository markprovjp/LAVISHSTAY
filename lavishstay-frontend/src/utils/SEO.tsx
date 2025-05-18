import { Helmet } from "react-helmet-async";
import React from "react";
import { env } from "../config";

interface SEOProps {
  title?: string;
  description?: string;
  image?: string;
  url?: string;
  type?: string;
}

/**
 * SEO Component
 * Uses react-helmet-async to manage document head
 * for better SEO optimization
 */
const SEO: React.FC<SEOProps> = ({
  title,
  description = "LavishStay - Nền tảng đặt phòng cao cấp",
  image = "/logo192.png",
  url = typeof window !== "undefined" ? window.location.href : "",
  type = "website",
}) => {
  const siteTitle = title ? `${title} | ${env.APP_NAME}` : env.APP_NAME;

  return (
    <Helmet>
      {/* Basic metadata */}
      <title>{siteTitle}</title>
      <meta name="description" content={description} />

      {/* Open Graph */}
      <meta property="og:type" content={type} />
      <meta property="og:title" content={siteTitle} />
      <meta property="og:description" content={description} />
      <meta property="og:image" content={image} />
      <meta property="og:url" content={url} />
      <meta property="og:site_name" content={env.APP_NAME} />

      {/* Twitter Card */}
      <meta name="twitter:card" content="summary_large_image" />
      <meta name="twitter:title" content={siteTitle} />
      <meta name="twitter:description" content={description} />
      <meta name="twitter:image" content={image} />

      {/* Canonical URL */}
      <link rel="canonical" href={url} />
    </Helmet>
  );
};

export default SEO;
