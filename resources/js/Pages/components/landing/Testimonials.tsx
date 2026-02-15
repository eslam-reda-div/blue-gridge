import { motion, useInView } from "framer-motion";
import { useRef } from "react";
import { Quote } from "lucide-react";

const testimonials = [
  {
    quote:
      "Green Bridge transformed our waste management. What used to be a cost center is now generating over $12,000 monthly in revenue.",
    name: "Sarah Mitchell",
    role: "Operations Director",
    company: "Grand Palace Hotel",
    emoji: "🏨",
  },
  {
    quote:
      "Finding reliable waste sources was our biggest challenge. Green Bridge gives us consistent, quality supply with zero hassle.",
    name: "Ahmed Al-Rashid",
    role: "CEO",
    company: "EcoRecycle Industries",
    emoji: "♻️",
  },
  {
    quote:
      "We reduced our raw material costs by 35% while meeting our sustainability goals. The platform's analytics are incredibly powerful.",
    name: "Maria Chen",
    role: "Procurement Head",
    company: "NovaPack Manufacturing",
    emoji: "🏭",
  },
];

const Testimonials = () => {
  const ref = useRef(null);
  const isInView = useInView(ref, { once: true, margin: "-100px" });

  return (
    <section className="py-20 lg:py-32 bg-background" ref={ref}>
      <div className="container mx-auto px-4 lg:px-8">
        <motion.div
          initial={{ opacity: 0, y: 30 }}
          animate={isInView ? { opacity: 1, y: 0 } : {}}
          transition={{ duration: 0.6 }}
          className="text-center mb-16"
        >
          <span className="inline-flex items-center gap-2 bg-primary/10 text-primary px-4 py-2 rounded-full text-sm font-medium mb-4">
            💬 Success Stories
          </span>
          <h2 className="text-3xl md:text-4xl lg:text-5xl font-display font-bold text-foreground mb-4">
            What Our Partners Say
          </h2>
        </motion.div>

        <div className="grid md:grid-cols-3 gap-8">
          {testimonials.map((t, i) => (
            <motion.div
              key={t.name}
              initial={{ opacity: 0, y: 30 }}
              animate={isInView ? { opacity: 1, y: 0 } : {}}
              transition={{ delay: i * 0.15, duration: 0.5 }}
              className="bg-card rounded-2xl p-8 border border-border shadow-earth hover:shadow-lg transition-shadow relative"
            >
              <Quote className="h-8 w-8 text-primary/20 mb-4" />
              <p className="text-foreground leading-relaxed mb-6 italic">"{t.quote}"</p>
              <div className="flex items-center gap-3">
                <div className="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center text-2xl">
                  {t.emoji}
                </div>
                <div>
                  <p className="font-semibold text-foreground">{t.name}</p>
                  <p className="text-sm text-muted-foreground">
                    {t.role}, {t.company}
                  </p>
                </div>
              </div>
            </motion.div>
          ))}
        </div>
      </div>
    </section>
  );
};

export default Testimonials;
