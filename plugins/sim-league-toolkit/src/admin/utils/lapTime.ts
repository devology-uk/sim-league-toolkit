const timePattern = /^(?:(\d+):)?(\d{1,2})(?:\.(\d{1,3}))?$/;

export const formatLapTimeMs = (ms: number | null): string => {
    if (ms === null) {
        return '';
    }

    const minutes = Math.floor(ms / 60000);
    const seconds = Math.floor((ms % 60000) / 1000);
    const millis = ms % 1000;

    const secondsPart = `${seconds}`.padStart(2, '0');
    const millisPart = `${millis}`.padStart(3, '0');

    return minutes > 0 ? `${minutes}:${secondsPart}.${millisPart}` : `${seconds}.${millisPart}`;
};

export const parseLapTimeMs = (value: string): number | null => {
    const trimmed = value.trim();

    if (!trimmed) {
        return null;
    }

    const match = timePattern.exec(trimmed);

    if (!match) {
        return null;
    }

    const minutes = match[1] ? parseInt(match[1], 10) : 0;
    const seconds = parseInt(match[2], 10);
    const millis = match[3] ? parseInt(match[3].padEnd(3, '0'), 10) : 0;

    return (minutes * 60 + seconds) * 1000 + millis;
};
