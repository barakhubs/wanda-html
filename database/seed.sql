-- ============================================================
-- database/seed.sql — Wanda Communications Uganda
-- Run AFTER schema.sql. Populates all tables with sample data.
-- ============================================================
-- ──────────────────────────────────────────────────────────────
-- TESTIMONIALS
-- ──────────────────────────────────────────────────────────────
INSERT INTO
  `testimonials` (
    `quote`,
    `author_initials`,
    `author_role`,
    `author_org`,
    `sort_order`,
    `published`
  )
VALUES
  (
    'Wanda Communications delivered an outstanding documentary that perfectly captured our advocacy work. Their professionalism and attention to detail exceeded our expectations.',
    'A.M.',
    'Programme Director',
    'UNICEF Uganda',
    1,
    1
  ),
  (
    'The strategic communication plan developed by Wanda helped us reach over 500,000 community members with our health messaging campaign. Truly transformative work.',
    'R.K.',
    'Communications Lead',
    'Ministry of Health',
    2,
    1
  ),
  (
    'Their photography and event coverage brought our annual gala to life in ways we never imagined. Every shot told a powerful story.',
    'S.N.',
    'Executive Director',
    'ActionAid Uganda',
    3,
    1
  );
-- ──────────────────────────────────────────────────────────────
  -- BLOG POSTS
  -- ──────────────────────────────────────────────────────────────
INSERT INTO
  `blog_posts` (
    `title`,
    `slug`,
    `category`,
    `excerpt`,
    `body`,
    `thumbnail`,
    `read_time`,
    `published`
  )
VALUES
  (
    'The Power of Visual Storytelling in Advocacy',
    'power-of-visual-storytelling-advocacy',
    'advocacy',
    'Discover how compelling visuals can transform advocacy campaigns and drive real change in communities across Uganda.',
    '<p>In an era of information overload, visual storytelling has emerged as one of the most powerful tools in the advocate''s toolkit. A single photograph or a short documentary can communicate what pages of text cannot — the human face of an issue.</p><p>At Wanda Communications Uganda, we have seen firsthand how strategic visual content transforms awareness into action. When communities see reflections of their own lives and challenges on screen, barriers dissolve and conversations begin.</p><h2>Why Visuals Work</h2><p>Research consistently shows that the human brain processes images 60,000 times faster than text. For advocacy campaigns targeting diverse audiences — including those with low literacy — visuals are not just preferable, they are essential.</p><p>Our documentary projects for health campaigns and civic education initiatives have demonstrated measurable increases in community engagement when visual content is placed at the centre of communication strategy.</p><h2>Building a Visual Advocacy Strategy</h2><ul><li><strong>Define your story arc:</strong> Every campaign needs a clear narrative with a protagonist, a challenge, and a path forward.</li><li><strong>Choose authentic voices:</strong> Community members speaking in their own words carry more credibility than polished corporate messaging.</li><li><strong>Distribute strategically:</strong> Great visuals shown only once accomplish little. Build a distribution plan that reaches your audience repeatedly.</li></ul><p>If your organisation is ready to harness the power of visual storytelling, our team is here to help you craft a strategy that moves hearts and changes minds.</p>',
    'images/photo gallery.JPG',
    6,
    1
  ),
  (
    'Strategic Communication Planning: A Step-by-Step Guide',
    'strategic-communication-planning-guide',
    'strategy',
    'Learn how to build a robust communication strategy that aligns with your organisational goals and reaches your target audience effectively.',
    '<p>A communication strategy is not simply a list of activities — it is a deliberate roadmap that connects your organisation''s mission to the people it exists to serve. At Wanda Communications, we guide nonprofits, government agencies, and private sector clients through a structured planning process that produces results.</p><h2>Step 1: Define Your Communication Objectives</h2><p>Your communication objectives should be SMART — Specific, Measurable, Achievable, Relevant, and Time-bound. Vague goals like "raise awareness" are insufficient. A better objective: "Reach 100,000 women aged 18–35 in northern Uganda with maternal health messaging within 6 months."</p><h2>Step 2: Know Your Audience</h2><p>Audience analysis is the foundation of effective communication. Conduct audience mapping exercises to understand demographics, media consumption habits, trust networks, and key barriers to behaviour change.</p><h2>Step 3: Craft Your Key Messages</h2><p>Key messages should be simple, consistent, and tailored to each audience segment. Avoid jargon. Test your messages with real community members before launching at scale.</p><h2>Step 4: Choose Your Channels</h2><p>In Uganda''s communication landscape, effective campaigns often blend community radio, social media, community dialogues, print materials, and visual content. The right mix depends on your audience analysis.</p><h2>Step 5: Measure and Adapt</h2><p>Build in clear milestones and monitoring frameworks from the start. Use data to adapt your strategy in real time — the best communication plans are living documents, not static blueprints.</p>',
    NULL,
    8,
    1
  ),
  (
    'Behind the Lens: Documenting Uganda''s Climate Advocates',
    'documenting-uganda-climate-advocates',
    'advocacy',
    'A look behind the camera as we documented the inspiring work of grassroots climate activists across Uganda for an international awareness campaign.',
    '<p>When the Africa Climate Summit brought global attention to the continent''s climate crisis, our team was deployed across five districts to document the work of local advocates who rarely make international headlines.</p><p>What we found — in the fishing communities of Lake Victoria, the drought-affected cattle corridors of Karamoja, and the reforestation projects of Mount Elgon — were stories of extraordinary resilience and innovation.</p><h2>The Challenge of Field Documentation</h2><p>Climate work happens in remote locations, often during extreme weather conditions. Our team adapted continuously — shooting in equatorial humidity, dusty northern plains, and the cold mountain forests of eastern Uganda.</p><p>The technical challenges were matched by emotional ones. Documenting communities facing existential threats requires sensitivity, trust, and genuine listening. We spent time in each community before raising a camera.</p><h2>The Impact</h2><p>The resulting documentary series was viewed over 2 million times across digital platforms and was screened at three international climate conferences. Several of the advocates we featured gained access to funding they had been unable to secure before their stories were told.</p><p>This is why visual documentation matters: it opens doors that policies alone cannot.</p>',
    'images/Group photo 2 gallery.JPG',
    5,
    1
  ),
  (
    'Media Relations in the Digital Age: What Nonprofits Need to Know',
    'media-relations-digital-age-nonprofits',
    'media',
    'Traditional media relations have evolved dramatically. Here is how Uganda''s CSOs can build effective relationships with journalists in a digital-first media landscape.',
    '<p>The media landscape in Uganda — and across Sub-Saharan Africa — has transformed dramatically over the last decade. Television and print remain influential, but digital platforms, WhatsApp broadcast lists, podcasts, and online newsrooms have created entirely new pathways to the public.</p><p>For civil society organisations, this creates both challenges and extraordinary opportunities.</p><h2>Understanding the Modern Journalist</h2><p>Today''s journalists are working faster than ever, often managing multiple platforms simultaneously. They value sources that are responsive, provide ready-to-use materials (high-resolution photos, B-roll footage, clear data visualisations), and can speak in quotable, non-jargon language.</p><h2>Building a Media List That Works</h2><p>A good media list is not simply a directory of newsrooms. It maps specific journalists to their beats and interests, includes mobile contacts (preferred in Uganda), and is updated at least quarterly.</p><h2>Crafting Press Materials That Get Used</h2><p>Press releases that arrive with strong accompanying visuals are significantly more likely to result in coverage. Our team always bundles press materials with high-resolution photography and, where possible, a short video summary for television and social media teams.</p><h2>Earned vs. Paid Media</h2><p>Invest in earned media relationships, but don''t neglect strategic paid placements — especially for radio, which remains the dominant medium in rural Uganda. A hybrid approach yields the strongest results.</p>',
    NULL,
    7,
    1
  ),
  (
    'Event Coverage Best Practices: Planning for Impact',
    'event-coverage-best-practices',
    'events',
    'From pre-event planning to post-event content distribution, here is how to ensure your events generate maximum media and social media impact.',
    '<p>Events are investments — in time, money, and organisational energy. Too often, that investment produces only a handful of photos on a hard drive. Strategic event coverage transforms a one-day gathering into weeks of content and media opportunities.</p><h2>Before the Event</h2><p>Event coverage planning should begin at least two weeks before the event. Identify your content goals: What stories do you want to tell? Which speakers or participants should be featured? What B-roll footage will your post-event video require?</p><p>Brief your photography and video teams with a shot list. Coordinate with logistics to ensure press access points, lighting considerations for indoor venues, and quiet spaces for interviews.</p><h2>During the Event</h2><p>Deploy your team across multiple simultaneous activities. While one photographer covers the main stage, another captures delegate interactions, exhibitions, and community participants. A videographer focused on reaction shots and testimonials provides essential content for social media.</p><h2>After the Event</h2><p>Speed is critical in post-event distribution. Same-day social media posts with strong images extend your reach while momentum is highest. A full post-event report with professional photography should be distributed to partners and media within 48 hours.</p>',
    NULL,
    4,
    1
  ),
  (
    'Building Community Dialogue: Lessons from the Field',
    'building-community-dialogue-lessons',
    'general',
    'Effective community engagement is not just about delivering messages — it is about creating genuine two-way dialogue. Here are key lessons from our field experience.',
    '<p>During a nutrition campaign in West Nile, our team discovered something that no communication textbook could have predicted: the communities we were trying to reach already had extensive knowledge about child nutrition. What was missing was not information — it was confidence, resources, and a sense that their voices mattered.</p><p>This experience reshaped how we approach community dialogue. Here are the lessons we carry forward to every engagement.</p><h2>Listen Before You Speak</h2><p>Effective community dialogue begins before any messages are crafted. Community mapping exercises, focus group discussions, and informal conversations reveal the communication ecosystem that already exists — including the trusted voices, the barriers to uptake, and the local language and framing that resonates.</p><h2>Work Through Existing Networks</h2><p>Every community has established networks — village health teams, women''s groups, religious congregations, local councils. Communication that moves through these existing channels travels with built-in trust and legitimacy.</p><h2>Create Space for Feedback</h2><p>Dialogue cannot be scripted. Build in genuine opportunities for communities to challenge, question, and redirect the conversation. The discomfort of losing control of your message is far outweighed by the insights you gain.</p><h2>Close the Loop</h2><p>Communities that share their concerns and never hear back quickly become disengaged. Even a brief follow-up — through community radios, notice boards, or local leaders — signals that their input was heard and valued.</p>',
    NULL,
    6,
    1
  );
-- ──────────────────────────────────────────────────────────────
  -- PORTFOLIO ITEMS
  -- ──────────────────────────────────────────────────────────────
INSERT INTO
  `portfolio_items` (
    `title`,
    `slug`,
    `category`,
    `short_desc`,
    `full_desc`,
    `thumbnail`,
    `gradient_css`,
    `icon_class`,
    `featured`,
    `sort_order`,
    `published`
  )
VALUES
  (
    'UNICEF Uganda Nutrition Campaign',
    'unicef-nutrition-campaign',
    'advocacy',
    'Strategic communication and visual documentation for a national nutrition advocacy campaign reaching 500,000+ community members.',
    'We partnered with UNICEF Uganda to develop a comprehensive communication strategy and documentary series for their national nutrition campaign. The project included community consultations, key message development, documentary filming across 8 districts, and a social media rollout that reached over half a million people.',
    'images/photo gallery.JPG',
    'linear-gradient(135deg, #1a6fc4 0%, #0d3f70 100%)',
    'bi-megaphone',
    1,
    1,
    1
  ),
  (
    'East Africa Climate Summit Documentation',
    'east-africa-climate-summit',
    'videography',
    'Full documentary coverage of the East Africa Climate Summit including delegate interviews, panel sessions, and community impact stories.',
    'Our team deployed across Kampala and five surrounding districts to capture the full story of the East Africa Climate Summit. The resulting documentary series included 12 short films, a 45-minute feature documentary, and 60+ broadcast-quality B-roll packages used by international media agencies.',
    'images/Group photo 2 gallery.JPG',
    'linear-gradient(135deg, #198754 0%, #0d4a2e 100%)',
    'bi-camera-video',
    1,
    2,
    1
  ),
  (
    'ActionAid Annual Gala Photography',
    'actionaid-annual-gala',
    'photography',
    'Professional event photography capturing the spirit and impact of ActionAid Uganda''s Annual Gala fundraiser.',
    'We provided comprehensive event photography services for ActionAid Uganda''s prestigious Annual Gala, delivering 500+ edited images that captured speaker sessions, donor interactions, award ceremonies, and the emotional storytelling moments that define high-impact fundraising events.',
    NULL,
    'linear-gradient(135deg, #e8b84b 0%, #c0891d 100%)',
    'bi-camera',
    1,
    3,
    1
  ),
  (
    'Ministry of Health Communication Strategy',
    'moh-communication-strategy',
    'reports',
    'Comprehensive strategic communication plan for Uganda''s Ministry of Health maternal and child health programme.',
    'We developed a multi-year Strategic Communication Plan for the Ministry of Health''s maternal and child health programme, including audience analysis, channel mapping, key message frameworks, monitoring and evaluation indicators, and a detailed implementation roadmap.',
    NULL,
    'linear-gradient(135deg, #7c3aed 0%, #3b0764 100%)',
    'bi-file-earmark-text',
    0,
    4,
    1
  ),
  (
    'NRM Presidential Campaign Media',
    'presidential-campaign-media',
    'photography',
    'Political campaign photography and media content for Uganda''s 2021 general election campaign trail.',
    'Embedded campaign photography and videography across the full election period. Our team documented rallies, community meetings, policy announcements, and the human moments that define political coverage. Materials were delivered daily for campaign communications teams.',
    NULL,
    'linear-gradient(135deg, #dc3545 0%, #7a0010 100%)',
    'bi-camera',
    0,
    5,
    1
  ),
  (
    'Kampala Corporate Brand Photography',
    'kampala-corporate-brand-photography',
    'photography',
    'Brand identity photography for Kampala-based financial services firms, NGOs, and hospitality clients.',
    'We produced professional brand photography packages for over 20 Kampala-based organisations across the financial services, hospitality, and development sectors. Services included executive portraiture, office environment photography, product photography, and team documentation.',
    NULL,
    'linear-gradient(135deg, #0d9488 0%, #042f2e 100%)',
    'bi-building',
    0,
    6,
    1
  );
-- ──────────────────────────────────────────────────────────────
  -- TEAM MEMBERS
  -- ──────────────────────────────────────────────────────────────
INSERT INTO
  `team_members` (
    `name`,
    `role`,
    `bio_1`,
    `bio_2`,
    `photo`,
    `gradient_css`,
    `fallback_icon`,
    `sort_order`,
    `published`
  )
VALUES
  (
    'Wanda Mugisha',
    'Founder & Executive Director',
    'Wanda Mugisha brings over 15 years of strategic communication experience across Uganda, East Africa, and internationally. With a background in journalism, advocacy, and organisational development, Wanda founded the agency with a vision to transform how African organisations tell their stories.',
    'She has led communication campaigns for UNICEF, WHO, the African Development Bank, and numerous leading civil society organisations. Wanda holds a Master''s degree in Strategic Communication from Makerere University and is a fellow of the African Leadership Institute.',
    NULL,
    'linear-gradient(135deg, #1a6fc4 0%, #0d3f70 100%)',
    'bi-person-fill',
    1,
    1
  ),
  (
    'Robert Kato',
    'Lead Photographer & Visual Director',
    'Robert is an award-winning documentary photographer and visual storyteller with 12 years of field experience across Sub-Saharan Africa. His work has appeared in The Guardian, Al Jazeera, and major international development publications.',
    'Robert leads all photography and videography productions at Wanda Communications, overseeing a team of four visual specialists. He holds a Bachelor''s in Fine Arts Photography from Uganda Christian University.',
    NULL,
    'linear-gradient(135deg, #198754 0%, #0d4a2e 100%)',
    'bi-camera-fill',
    2,
    1
  ),
  (
    'Sarah Namukasa',
    'Advocacy & Communications Specialist',
    'Sarah specialises in advocacy campaign design, community mobilisation, and behaviour change communication. She has over eight years of experience working with health, education, and governance programmes across Uganda.',
    'Before joining Wanda Communications, Sarah led communications for the Population Secretariat of Uganda and managed USAID-funded health communication initiatives in the Acholi and Lango sub-regions.',
    NULL,
    'linear-gradient(135deg, #e8b84b 0%, #c0891d 100%)',
    'bi-megaphone-fill',
    3,
    1
  ),
  (
    'David Ssebuguzi',
    'Digital Media & Content Strategist',
    'David manages Wanda Communications'' digital production capabilities, from social media strategy to multimedia content development and online campaign management. He brings a data-driven approach to content planning and audience growth.',
    'With a background in computer science and digital marketing, David bridges the gap between technical digital tools and creative communication work. He is certified in Google Analytics, Meta Business, and HubSpot content marketing.',
    NULL,
    'linear-gradient(135deg, #7c3aed 0%, #3b0764 100%)',
    'bi-laptop-fill',
    4,
    1
  );
-- ──────────────────────────────────────────────────────────────
  -- TEAM SKILLS
  -- ──────────────────────────────────────────────────────────────
  -- Get IDs by position (assumes sequential IDs starting at 1)
  -- Wanda Mugisha (id=1)
INSERT INTO
  `team_skills` (`member_id`, `skill_name`, `sort_order`)
VALUES
  (1, 'Strategic Communication', 1),
  (1, 'Advocacy Planning', 2),
  (1, 'Organisational Development', 3),
  (1, 'Media Relations', 4),
  (1, 'Stakeholder Engagement', 5),
  (1, 'Fund Development', 6);
-- Robert Kato (id=2)
INSERT INTO
  `team_skills` (`member_id`, `skill_name`, `sort_order`)
VALUES
  (2, 'Documentary Photography', 1),
  (2, 'Video Production', 2),
  (2, 'Photo Editing', 3),
  (2, 'Drone Operations', 4),
  (2, 'Field Documentation', 5),
  (2, 'Brand Photography', 6);
-- Sarah Namukasa (id=3)
INSERT INTO
  `team_skills` (`member_id`, `skill_name`, `sort_order`)
VALUES
  (3, 'Advocacy Campaigns', 1),
  (3, 'Behaviour Change Communication', 2),
  (3, 'Community Mobilisation', 3),
  (3, 'Monitoring & Evaluation', 4),
  (3, 'Radio Production', 5);
-- David Ssebuguzi (id=4)
INSERT INTO
  `team_skills` (`member_id`, `skill_name`, `sort_order`)
VALUES
  (4, 'Social Media Strategy', 1),
  (4, 'Content Creation', 2),
  (4, 'SEO & Analytics', 3),
  (4, 'Email Marketing', 4),
  (4, 'Graphic Design', 5);
-- ──────────────────────────────────────────────────────────────
  -- HOME GALLERY (6 images from existing /images/ folder)
  -- ──────────────────────────────────────────────────────────────
INSERT INTO
  `home_gallery` (
    `image_path`,
    `alt_text`,
    `is_wide`,
    `sort_order`,
    `published`
  )
VALUES
  (
    'images/photo gallery.JPG',
    'Wanda Communications photo documentation',
    1,
    1,
    1
  ),
  (
    'images/Group photo 2 gallery.JPG',
    'Team group photo at a community engagement event',
    0,
    2,
    1
  ),
  (
    'images/video production.JPG',
    'Video production in the field',
    0,
    3,
    1
  ),
  (
    'images/advocacy campaign.JPG',
    'Advocacy campaign community outreach',
    0,
    4,
    1
  ),
  (
    'images/event coverage.JPG',
    'Professional event coverage',
    0,
    5,
    1
  ),
  (
    'images/documentary filming.JPG',
    'Documentary filming on location',
    0,
    6,
    1
  );