import { motion } from "framer-motion";
import { ArrowRight, Play, Leaf, Recycle, Factory } from "lucide-react";
import { Button } from "@/components/ui/button";

const HeroSection = () => {
  return (
    <section className="relative min-h-screen flex items-center overflow-hidden bg-gradient-hero pt-20">
      {/* Floating decorative leaves */}
      <motion.div
        animate={{ y: [-10, 10, -10], rotate: [0, 10, 0] }}
        transition={{ duration: 8, repeat: Infinity }}
        className="absolute top-32 left-[10%] opacity-20"
      >
        <Leaf className="h-16 w-16 text-primary" />
      </motion.div>
      <motion.div
        animate={{ y: [10, -15, 10], rotate: [0, -8, 0] }}
        transition={{ duration: 10, repeat: Infinity }}
        className="absolute top-48 right-[15%] opacity-15"
      >
        <Leaf className="h-12 w-12 text-eco-olive" />
      </motion.div>
      <motion.div
        animate={{ y: [5, -20, 5] }}
        transition={{ duration: 7, repeat: Infinity }}
        className="absolute bottom-32 left-[20%] opacity-10"
      >
        <Leaf className="h-20 w-20 text-eco-forest" />
      </motion.div>

      <div className="container mx-auto px-4 lg:px-8">
        <div className="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">
          {/* Text Content */}
          <motion.div
            initial={{ opacity: 0, x: -40 }}
            animate={{ opacity: 1, x: 0 }}
            transition={{ duration: 0.8 }}
          >
            <motion.div
              initial={{ opacity: 0, y: 20 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ delay: 0.2 }}
              className="inline-flex items-center gap-2 bg-primary/10 text-primary px-4 py-2 rounded-full text-sm font-medium mb-6"
            >
              <Recycle className="h-4 w-4" />
              Circular Economy Platform
            </motion.div>

            <h1 className="text-4xl md:text-5xl lg:text-6xl font-display font-bold text-foreground leading-tight mb-6">
              Turning Waste Into{" "}
              <span className="text-gradient-green">Wealth</span>
              <br />
              <span className="text-3xl md:text-4xl lg:text-5xl">
                Bridging the Gap in the Circular Economy
              </span>
            </h1>

            <p className="text-lg text-muted-foreground max-w-xl mb-8 leading-relaxed">
              Green Bridge connects waste generators, recycling suppliers, and factories
              into one seamless ecosystem — automating the entire process from waste
              collection to product manufacturing.
            </p>

            <div className="flex flex-col sm:flex-row gap-4">
              <Button
                size="lg"
                className="bg-primary hover:bg-eco-forest-light text-primary-foreground font-semibold px-8 rounded-full text-base shadow-green"
              >
                Request a Demo
                <ArrowRight className="ml-2 h-5 w-5" />
              </Button>
              <Button
                size="lg"
                variant="outline"
                className="border-primary text-primary hover:bg-primary/5 font-semibold px-8 rounded-full text-base"
              >
                <Play className="mr-2 h-5 w-5" />
                Learn More
              </Button>
            </div>
          </motion.div>

          {/* Circular Flow Diagram */}
          <motion.div
            initial={{ opacity: 0, scale: 0.8 }}
            animate={{ opacity: 1, scale: 1 }}
            transition={{ duration: 0.8, delay: 0.3 }}
            className="relative flex items-center justify-center"
          >
            <div className="relative w-80 h-80 md:w-96 md:h-96">
              {/* Central circle */}
              <div className="absolute inset-0 flex items-center justify-center">
                <div className="w-24 h-24 rounded-full bg-primary/10 flex items-center justify-center border-2 border-primary/30">
                  <Recycle className="h-10 w-10 text-primary" />
                </div>
              </div>

              {/* Orbiting nodes */}
              <motion.div
                animate={{ rotate: 360 }}
                transition={{ duration: 20, repeat: Infinity, ease: "linear" }}
                className="absolute inset-0"
              >
                {/* Waste Generator */}
                <div className="absolute top-0 left-1/2 -translate-x-1/2 -translate-y-2">
                  <motion.div
                    animate={{ rotate: -360 }}
                    transition={{ duration: 20, repeat: Infinity, ease: "linear" }}
                    className="w-20 h-20 rounded-2xl bg-card shadow-earth flex flex-col items-center justify-center border border-border"
                  >
                    <span className="text-2xl">🏨</span>
                    <span className="text-[10px] font-medium text-muted-foreground mt-1">Generators</span>
                  </motion.div>
                </div>

                {/* Supplier */}
                <div className="absolute bottom-4 left-4">
                  <motion.div
                    animate={{ rotate: -360 }}
                    transition={{ duration: 20, repeat: Infinity, ease: "linear" }}
                    className="w-20 h-20 rounded-2xl bg-card shadow-earth flex flex-col items-center justify-center border border-border"
                  >
                    <span className="text-2xl">♻️</span>
                    <span className="text-[10px] font-medium text-muted-foreground mt-1">Suppliers</span>
                  </motion.div>
                </div>

                {/* Factory */}
                <div className="absolute bottom-4 right-4">
                  <motion.div
                    animate={{ rotate: -360 }}
                    transition={{ duration: 20, repeat: Infinity, ease: "linear" }}
                    className="w-20 h-20 rounded-2xl bg-card shadow-earth flex flex-col items-center justify-center border border-border"
                  >
                    <Factory className="h-6 w-6 text-secondary" />
                    <span className="text-[10px] font-medium text-muted-foreground mt-1">Factories</span>
                  </motion.div>
                </div>
              </motion.div>

              {/* Orbit ring */}
              <div className="absolute inset-4 rounded-full border-2 border-dashed border-primary/20" />
            </div>
          </motion.div>
        </div>
      </div>
    </section>
  );
};

export default HeroSection;
