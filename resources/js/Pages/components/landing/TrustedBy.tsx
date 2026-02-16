import { motion, useInView } from "framer-motion";
import { useRef } from "react";

const partners = [
  "Grand Palace Hotels", "EcoRecycle Co.", "NovaPack Mfg.", "CityMall Group",
  "GreenWaste Inc.", "PureCircle Ltd.", "MetroParks Authority", "RecycleTech",
  "UrbanHarvest", "CleanLoop Systems", "BioMaterial Corp.", "EarthFirst Logistics",
];

const TrustedBy = () => {
  const ref = useRef(null);
  const isInView = useInView(ref, { once: true, margin: "-50px" });

  return (
    <section className="py-16 bg-card border-y border-border overflow-hidden" ref={ref}>
      <div className="container mx-auto px-4 lg:px-8 mb-8">
        <motion.p
          initial={{ opacity: 0 }}
          animate={isInView ? { opacity: 1 } : {}}
          className="text-center text-sm font-medium text-muted-foreground uppercase tracking-wider"
        >
          Trusted by Industry Leaders
        </motion.p>
      </div>

      {/* Scrolling logos */}
      <div className="relative">
        <div className="flex animate-scroll-left">
          {[...partners, ...partners].map((name, i) => (
            <div
              key={i}
              className="shrink-0 mx-8 px-6 py-3 bg-background rounded-xl border border-border"
            >
              <span className="text-sm font-semibold text-muted-foreground whitespace-nowrap">
                {name}
              </span>
            </div>
          ))}
        </div>
      </div>
    </section>
  );
};

export default TrustedBy;
