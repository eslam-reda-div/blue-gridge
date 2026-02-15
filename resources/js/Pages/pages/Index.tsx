import Navbar from "@/components/landing/Navbar";
import HeroSection from "@/components/landing/HeroSection";
import HowItWorks from "@/components/landing/HowItWorks";
import StakeholderSpotlight from "@/components/landing/StakeholderSpotlight";
import DashboardPreview from "@/components/landing/DashboardPreview";
import FeaturesGrid from "@/components/landing/FeaturesGrid";
import EnvironmentalImpact from "@/components/landing/EnvironmentalImpact";
import Testimonials from "@/components/landing/Testimonials";
import TrustedBy from "@/components/landing/TrustedBy";
import PricingSection from "@/components/landing/PricingSection";
import FAQSection from "@/components/landing/FAQSection";
import CTASection from "@/components/landing/CTASection";
import Footer from "@/components/landing/Footer";

const Index = () => {
  return (
    <div className="min-h-screen bg-background">
      <Navbar />
      <HeroSection />
      <HowItWorks />
      <StakeholderSpotlight />
      <DashboardPreview />
      <FeaturesGrid />
      <EnvironmentalImpact />
      <Testimonials />
      <TrustedBy />
      <PricingSection />
      <FAQSection />
      <CTASection />
      <Footer />
    </div>
  );
};

export default Index;
