import { Leaf } from "lucide-react";

const footerLinks = {
  Platform: ["How It Works", "Features", "Pricing", "Analytics"],
  Company: ["About Us", "Careers", "Blog", "Press"],
  Resources: ["Documentation", "API", "Support", "Community"],
  Legal: ["Privacy Policy", "Terms of Service", "Cookie Policy"],
};

const Footer = () => {
  return (
    <footer className="bg-foreground text-primary-foreground py-16">
      <div className="container mx-auto px-4 lg:px-8">
        <div className="grid md:grid-cols-2 lg:grid-cols-5 gap-12 mb-12">
          {/* Brand */}
          <div className="lg:col-span-1">
            <div className="flex items-center gap-2 mb-4">
              <Leaf className="h-7 w-7 text-eco-leaf" />
              <span className="text-xl font-display font-bold">
                Green<span className="text-eco-leaf">Bridge</span>
              </span>
            </div>
            <p className="text-sm text-primary-foreground/60 leading-relaxed">
              Building a sustainable future by connecting waste generators, recyclers,
              and manufacturers.
            </p>
          </div>

          {/* Links */}
          {Object.entries(footerLinks).map(([category, links]) => (
            <div key={category}>
              <h4 className="font-semibold text-sm mb-4 uppercase tracking-wider text-primary-foreground/80">
                {category}
              </h4>
              <ul className="space-y-2">
                {links.map((link) => (
                  <li key={link}>
                    <a
                      href="#"
                      className="text-sm text-primary-foreground/50 hover:text-eco-leaf transition-colors"
                    >
                      {link}
                    </a>
                  </li>
                ))}
              </ul>
            </div>
          ))}
        </div>

        <div className="border-t border-primary-foreground/10 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
          <p className="text-sm text-primary-foreground/40">
            © 2026 Green Bridge. All rights reserved.
          </p>
          <p className="text-sm text-primary-foreground/40 flex items-center gap-1">
            Made with <Leaf className="h-4 w-4 text-eco-leaf" /> for a sustainable future
          </p>
        </div>
      </div>
    </footer>
  );
};

export default Footer;
