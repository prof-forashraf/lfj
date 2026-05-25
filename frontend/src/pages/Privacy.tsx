import React from "react";
import { Link } from "react-router-dom";
import { motion } from "framer-motion";

// --- UPGRADED: Thematic icons ---
import {
  Shield,
  Database,
  Cookie,
  Handshake,
  Users,
  Mail,
  BookUser,
  FileText,
} from "lucide-react";

// --- Reusable Components ---
import SEOMetaTags from "@/components/blog/SEOMetaTags";
import { Card, CardHeader, CardTitle, CardContent } from "@/components/ui/card";
import {
  Breadcrumb,
  BreadcrumbItem,
  BreadcrumbLink,
  BreadcrumbList,
  BreadcrumbPage,
  BreadcrumbSeparator,
} from "@/components/ui/breadcrumb";

// --- Data for our structured layout ---
const privacySections = [
  { id: "introduction", title: "1. Introduction" },
  { id: "information-we-collect", title: "2. Information We Collect" },
  { id: "how-we-use-info", title: "3. How We Use Your Information" },
  { id: "affiliate-cookies", title: "4. Affiliate & Cookies" },
  { id: "data-sharing", title: "5. Data Sharing & Disclosure" },
  { id: "data-security", title: "6. Data Security" },
  { id: "your-data-rights", title: "7. Your Data Rights" },
  { id: "policy-changes", title: "8. Changes to This Policy" },
  { id: "contact-us", title: "9. Contact Us" },
];

const Privacy: React.FC = () => {
  return (
    <>
      <SEOMetaTags
        title="Privacy Policy | Latest Fashion Jewellery"
        description="Understand how Latest Fashion Jewellery handles your data. Our privacy policy details our practices on data collection, usage, and protection."
        keywords="privacy policy, data protection, personal information, cookie policy, GDPR"
      />

      <main className="mt-20 pb-16 bg-soft-cream text-dark-slate">
        <div className="container mx-auto px-4 py-12">
          <div className="mb-8">
            <Breadcrumb>{/* ... Breadcrumb code ... */}</Breadcrumb>
          </div>

          <header className="mb-16">
            <h1 className="text-4xl md:text-5xl font-playfair font-bold text-dark-slate mb-2">
              Privacy Policy
            </h1>
            <p className="text-muted-foreground">Last Updated: May 1, 2025</p>
          </header>

          <div className="grid grid-cols-1 lg:grid-cols-4 gap-12">
            {/* Left Column: Navigation */}
            <aside className="lg:col-span-1">
              <div className="sticky top-28">
                <h3 className="font-semibold text-lg mb-4">Policy Sections</h3>
                <ul className="space-y-2">
                  {privacySections.map((section) => (
                    <li key={section.id}>
                      <a
                        href={`#${section.id}`}
                        className="text-muted-foreground hover:text-primary-gold transition-colors"
                      >
                        {section.title}
                      </a>
                    </li>
                  ))}
                </ul>
              </div>
            </aside>

            {/* Right Column: Content */}
            <motion.div
              className="lg:col-span-3 space-y-8"
              initial={{ opacity: 0, y: 20 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ duration: 0.5 }}
            >
              <PolicySection
                id="introduction"
                icon={FileText}
                title="1. Introduction"
              >
                <p>
                  At Latest Fashion Jewellery, your privacy is a top priority.
                  This policy outlines how we collect, use, protect, and handle
                  your personal data when you visit our website. By using our
                  site, you agree to the data practices described in this
                  statement.
                </p>
              </PolicySection>

              <PolicySection
                id="information-we-collect"
                icon={Database}
                title="2. Information We Collect"
              >
                <p>
                  We may collect non-personally identifiable information like
                  your browser type, IP address, and pages visited to improve
                  our site's performance (Usage Data). We only collect
                  personally identifiable information, such as your name and
                  email, if you voluntarily provide it to us by subscribing to
                  our newsletter or using our contact form.
                </p>
              </PolicySection>

              <PolicySection
                id="how-we-use-info"
                icon={Users}
                title="3. How We Use Your Information"
              >
                <p>
                  Your data helps us to provide and improve our service. We use
                  it to monitor website usage, address technical issues, provide
                  customer support, and, with your consent, send you newsletters
                  and marketing updates. We are committed to using your data
                  responsibly and only for the purposes stated.
                </p>
              </PolicySection>

              <PolicySection
                id="affiliate-cookies"
                icon={Cookie}
                title="4. Affiliate Disclosure & Cookies"
              >
                <p>
                  Our site uses affiliate links (e.g., from Amazon). When you
                  click these links, a cookie is placed on your browser to track
                  the referral. This is essential for our business model and
                  allows us to earn a commission at no extra cost to you. We
                  also use essential and analytical cookies to ensure our
                  website functions correctly and to understand user behavior.
                </p>
              </PolicySection>

              <PolicySection
                id="data-sharing"
                icon={Handshake}
                title="5. Data Sharing & Disclosure"
              >
                <p>
                  We do not sell your personal data. We may share it with
                  trusted third-party service providers who assist us in
                  operating our website (e.g., email marketing platforms). We
                  may also disclose data if required by law or in connection
                  with a major business transaction like a merger.
                </p>
              </PolicySection>

              <PolicySection
                id="data-security"
                icon={Shield}
                title="6. Data Security"
              >
                <p>
                  We implement a variety of security measures to maintain the
                  safety of your personal information. However, please be aware
                  that no method of electronic transmission or storage is 100%
                  secure, and we cannot guarantee absolute security.
                </p>
              </PolicySection>

              <PolicySection
                id="your-data-rights"
                icon={BookUser}
                title="7. Your Data Rights"
              >
                <p>
                  You have the right to access, rectify, or erase your personal
                  data. You can also object to or restrict certain types of data
                  processing. To exercise these rights, please contact us. You
                  can unsubscribe from our mailing list at any time by clicking
                  the "unsubscribe" link in our emails.
                </p>
              </PolicySection>

              <PolicySection
                id="policy-changes"
                title="8. Changes to This Policy"
              >
                <p>
                  We may update this Privacy Policy periodically. We will notify
                  users by updating the "Last Updated" date at the top of this
                  page. We encourage you to review this policy regularly to stay
                  informed.
                </p>
              </PolicySection>

              <PolicySection id="contact-us" icon={Mail} title="9. Contact Us">
                <p>
                  If you have any questions or concerns regarding this Privacy
                  Policy, please contact us via our{" "}
                  <Link
                    to="/contact"
                    className="font-semibold text-primary-gold hover:underline"
                  >
                    Contact Page
                  </Link>{" "}
                  or by emailing us directly at{" "}
                  <a
                    href="mailto:privacy@latestfashionjewellery.com"
                    className="font-semibold text-primary-gold hover:underline"
                  >
                    privacy@latestfashionjewellery.com
                  </a>
                  .
                </p>
              </PolicySection>
            </motion.div>
          </div>
        </div>
      </main>

    </>
  );
};

// --- Reusable Sub-Component for this page ---
const PolicySection: React.FC<{
  id: string;
  icon?: React.ElementType;
  title: string;
  children: React.ReactNode;
}> = ({ id, icon: Icon, title, children }) => (
  <Card id={id} className="bg-white shadow-sm scroll-mt-24">
    <CardHeader>
      <div className="flex items-center gap-3">
        {Icon && <Icon className="h-6 w-6 text-primary-gold" />}
        <CardTitle className="font-playfair text-2xl text-dark-slate">
          {title}
        </CardTitle>
      </div>
    </CardHeader>
    <CardContent className="space-y-4 text-muted-foreground leading-relaxed prose-p:my-0">
      {children}
    </CardContent>
  </Card>
);

export default Privacy;
