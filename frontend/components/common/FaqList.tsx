/** Daftar FAQ native yang tetap berfungsi tanpa JavaScript. */
export default function FaqList({
  items,
}: {
  items: Array<{ question: string; answer: string }>;
}) {
  return (
    <div className="divide-y divide-line border-y border-line">
      {items.map((faq) => (
        <details key={faq.question} className="group py-5">
          <summary className="cursor-pointer list-none pr-8 font-bold text-navy marker:hidden">
            {faq.question}
          </summary>
          <p className="mt-3 max-w-3xl text-sm leading-7 text-muted">{faq.answer}</p>
        </details>
      ))}
    </div>
  );
}
