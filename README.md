## Activity Feed Posts

Posts are Markdown files with Front Matter to provide a more comfortable writing experience and be easily ported to/from Jekyll.

### File Names

Post files should be stored directly in `_posts` and follow the regular Jekyll format:

```
./_posts/YYYY-MM-DD-slug.md
```

### Front Matter

Here are the front matter variables (with example values) that you should include in each post:

```yaml
---
title: "Improved Page Load by 65% with Memoized Rendering" # Full article title with proper capitalization, preferably including a KPI metric.
short: "Just shipped a React optimization that cut load times by 3.2 seconds 🚀 Client is thrilled!" # Twitter-like teaser humanizing this post for activity feed display.
images: # Optional. Array of related images to accompany the post.
  - src: "/images/cat.jpg"
    caption: "A cute cat"
  - src: "/images/dog.jpg"
    caption: "A happy dog"
  - src: "/images/bird.jpg"
    caption: "A colorful bird"
---
```

### Content

Posts should be as succinct and skimmable as possible with a professional, fact-driven tone.

Measurable KPI outcomes are preferred for immediately identifiable and relatable value.

Prefer 1-2 sentences per paragraph following this structure:

- **Problem** – The situation or observation which prompted the work.
- **Solution** – The implementation or changes that solved the problem.
- **Impact** – The positive outcomes of the problem being resolved or the added benefits provided by the solution.
