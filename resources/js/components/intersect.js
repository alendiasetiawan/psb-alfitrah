export default function (Alpine) {
   Alpine.directive(
      'intersect',
      (el, { modifiers, expression }, { evaluateLater, cleanup }) => {
         let evaluate = evaluateLater(expression);

         let observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
               if (entry.isIntersecting) {
                  evaluate();
                  if (!modifiers.includes('multiple')) {
                     observer.unobserve(el);
                  }
               }
            });
         });

         observer.observe(el);

         cleanup(() => observer.disconnect());
      }
   );
}
