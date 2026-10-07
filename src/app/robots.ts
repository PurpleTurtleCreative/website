import type { MetadataRoute } from "next";
import { PRODUCTION_SITE_URL } from "@/util/constants";

export const dynamic = "force-static";

export default function robots(): MetadataRoute.Robots {
  return {
    rules: {
      userAgent: "*",
      allow: "/",
      disallow: ["/portal/"],
    },
    sitemap: `${PRODUCTION_SITE_URL}/sitemap.xml`,
  };
}
