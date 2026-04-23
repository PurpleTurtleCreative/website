import type { NextConfig } from "next";

const nextConfig: NextConfig = {
  output: "export",
  trailingSlash: true, // Output /new-page/index.html instead of /new-page.html so DigitalOcean routing automatically works.
  images: {
    unoptimized: true,
  },
};

export default nextConfig;
