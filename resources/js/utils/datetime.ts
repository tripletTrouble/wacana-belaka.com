export function formatRelative(date: string | number | Date | null | undefined, locale = 'id'): string {
  if (!date) return '—';

  const d = new Date(date);
  const now = Date.now();
  const seconds = Math.floor((d.getTime() - now) / 1000);
  const rtf = new Intl.RelativeTimeFormat(locale, { numeric: 'auto' });

  const units: { unit: Intl.RelativeTimeFormatUnit; secs: number }[] = [
    { unit: 'year', secs: 365 * 24 * 60 * 60 },
    { unit: 'month', secs: 30 * 24 * 60 * 60 },
    { unit: 'day', secs: 24 * 60 * 60 },
    { unit: 'hour', secs: 60 * 60 },
    { unit: 'minute', secs: 60 },
    { unit: 'second', secs: 1 },
  ];

  for (const { unit, secs } of units) {
    if (Math.abs(seconds) >= secs || unit === 'second') {
      const val = Math.round(seconds / secs);
      return rtf.format(val, unit);
    }
  }

  return d.toLocaleString('id-ID');
}

export function formatLocale(date: string | number | Date | null | undefined, locale = 'id-ID'): string {
  if (!date) return '—';
  return new Date(date).toLocaleString(locale);
}

export default { formatRelative, formatLocale };
