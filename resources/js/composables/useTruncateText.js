export function useTruncateText() {
    const truncate = (text, limit, suffix = '...') => {
      if (typeof text !== 'string') return '';
      if (text.length <= limit) return text;
      return text.slice(0, limit) + suffix;
    };
  
    return { truncate };
  }