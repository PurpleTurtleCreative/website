import type { MetadataRoute } from "next";
import { PRODUCTION_SITE_URL } from "@/util/constants";

export const dynamic = "force-static";

export default function sitemap(): MetadataRoute.Sitemap {
  return [
    {
      url: `${PRODUCTION_SITE_URL}/`,
      lastModified: new Date(),
      changeFrequency: "monthly",
      priority: 1,
    },
  ];
}
