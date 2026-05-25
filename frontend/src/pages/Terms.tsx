import React from "react";
import { Link } from "react-router-dom";
import { motion } from "framer-motion";

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
const termsSections = [
  { id: "introduction", title: "1. Introduction" },
  { id: "affiliate-disclosure", title: "2. Affiliate Disclosure" },
  { id: "website-use", title: "3. Website Use" },
  { id: "product-information", title: "4. Product Information" },
  { id: "intellectual-property", title: "5. Intellectual Property" },
  { id: "limitation-of-liability", title: "6. Limitation of Liability" },
  { id: "privacy-policy", title: "7. Privacy Policy" },
  { id: "modifications", title: "8. Modifications to Terms" },
  { id: "contact-us", title: "9. Contact Us" },
];

const Terms: React.FC = () => {
  return (
    <>
      <SEOMetaTags
        title="Terms & Conditions | Latest Fashion Jewellery"
        description="Review the Terms and Conditions governing the use of the Latest Fashion Jewellery website. Understand our policies, affiliate disclosures, and guidelines."
        keywords="terms and conditions, legal, policies, affiliate terms, website terms"
      />

      <main className="mt-20 pb-16 bg-soft-cream text-dark-slate">
        <div className="container mx-auto px-4 py-12">
          <div className="mb-8">
            <Breadcrumb>{/* ... Breadcrumb code ... */}</Breadcrumb>
          </div>

          <header className="mb-16">
            <h1 className="text-4xl md:text-5xl font-playfair font-bold text-dark-slate mb-2">
              Terms & Conditions
            </h1>
            <p className="text-muted-foreground">Last Updated: May 1, 2025</p>
          </header>

          {/* --- UPGRADED: Two-column layout with sticky navigation --- */}
          <div className="grid grid-cols-1 lg:grid-cols-4 gap-12">
            {/* Left Column: Navigation */}
            <aside className="lg:col-span-1">
              <div className="sticky top-28">
                <h3 className="font-semibold text-lg mb-4">On this page</h3>
                <ul className="space-y-2">
                  {termsSections.map((section) => (
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
              <TermSection id="introduction" title="1. Introduction">
                <p>
                  Welcome to Latest Fashion Jewellery ("we," "our," or "us").
                  These Terms and Conditions govern your use of our website and
                  form a binding legal agreement between you and our platform.
                  By accessing or using our website, you agree to be bound by
                  these terms. If you disagree with any part, you may not access
                  the service.
                </p>
              </TermSection>

              <TermSection
                id="affiliate-disclosure"
                title="2. Affiliate Disclosure"
              >
                <p>
                  Latest Fashion Jewellery is a participant in the Amazon
                  Services LLC Associates Program and other affiliate programs.
                  This means we may earn a commission on qualifying purchases
                  made through our links, at no extra cost to you. We are not
                  the seller or merchant of record for these items and are not
                  responsible for the actions, products, or content of our
                  third-party retail partners.
                </p>
              </TermSection>

              <TermSection id="website-use" title="3. Website Use">
                <p>
                  You agree to use this website lawfully and in a manner that
                  does not infringe upon the rights of, or restrict or inhibit
                  the use and enjoyment of, this site by any third party.
                  Prohibited behavior includes harassing or causing distress to
                  any person, transmitting obscene content, or disrupting the
                  normal flow of dialogue.
                </p>
              </TermSection>

              <TermSection
                id="product-information"
                title="4. Product Information"
              >
                <p>
                  We strive to provide accurate product information, including
                  descriptions and images. However, we do not warrant that
                  product descriptions or other content are accurate, complete,
                  reliable, current, or error-free. All transactions, pricing,
                  and fulfillment are handled by the third-party retailer (e.g.,
                  Amazon).
                </p>
              </TermSection>

              <TermSection
                id="intellectual-property"
                title="5. Intellectual Property"
              >
                <p>
                  All content on this website, including text, graphics, logos,
                  and images, is the property of Latest Fashion Jewellery or its
                  content suppliers and protected by international copyright
                  laws. Unauthorized use, reproduction, or distribution is
                  strictly prohibited.
                </p>
              </TermSection>

              <TermSection
                id="limitation-of-liability"
                title="6. Limitation of Liability"
              >
                <p>
                  Our website and its content are provided on an "as is" basis.
                  Latest Fashion Jewellery shall not be liable for any direct,
                  indirect, incidental, or consequential damages resulting from
                  your use of, or inability to use, our website or the
                  information provided.
                </p>
              </TermSection>

              <TermSection id="privacy-policy" title="7. Privacy Policy">
                <p>
                  Your privacy is important to us. Our use of your personal
                  information is governed by our Privacy Policy, which you can
                  review{" "}
                  <Link
                    to="/privacy"
                    className="font-semibold text-primary-gold hover:underline"
                  >
                    here
                  </Link>
                  .
                </p>
              </TermSection>

              <TermSection id="modifications" title="8. Modifications to Terms">
                <p>
                  We reserve the right to modify these Terms and Conditions at
                  any time. We will notify users by updating the "Last Updated"
                  date at the top of this page. Your continued use of the site
                  after such changes constitutes your acceptance of the new
                  terms.
                </p>
              </TermSection>

              <TermSection id="contact-us" title="9. Contact Us">
                <p>
                  If you have any questions about these Terms and Conditions,
                  please reach out to us via our{" "}
                  <Link
                    to="/contact"
                    className="font-semibold text-primary-gold hover:underline"
                  >
                    Contact Page
                  </Link>{" "}
                  or by email at{" "}
                  <a
                    href="mailto:info@latestfashionjewellery.com"
                    className="font-semibold text-primary-gold hover:underline"
                  >
                    info@latestfashionjewellery.com
                  </a>
                  .
                </p>
              </TermSection>
            </motion.div>
          </div>
        </div>
      </main>

    </>
  );
};

// --- Reusable Sub-Component for this page ---
const TermSection: React.FC<{
  id: string;
  title: string;
  children: React.ReactNode;
}> = ({ id, title, children }) => (
  <Card id={id} className="bg-white shadow-sm scroll-mt-24">
    <CardHeader>
      <CardTitle className="font-playfair text-2xl text-dark-slate">
        {title}
      </CardTitle>
    </CardHeader>
    <CardContent className="space-y-4 text-muted-foreground leading-relaxed">
      {children}
    </CardContent>
  </Card>
);

export default Terms;
