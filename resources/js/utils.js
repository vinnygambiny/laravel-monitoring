import {
    toDate,
    format,
    formatDuration,
    intervalToDuration,
    parseISO,
    differenceInSeconds,
    formatDistanceToNow,
} from 'date-fns';

export function formatDateUnixTime(unixTime) {
    return toDate(unixTime * 1000);
}

export function formatDateString(string) {
    return toDate(parseISO(string));
}

/**
 * Convert to human readable timestamp.
 */
export function readableTimestamp(timestamp) {
    return format(formatDateUnixTime(timestamp), 'yyyy-mm-dd HH:mm:ss');
}

export function readableTime(date) {
    return format(formatDateString(date), 'yyyy-mm-dd HH:mm:ss');
}

export function readableTimeSeconds(seconds) {
    const durations = intervalToDuration({ start: 0, end: seconds * 1000 });
    return formatDuration(durations);
}

export function determinePeriod(minutes) {
    const durations = intervalToDuration({ start: 0, end: minutes * 60000 });
    return formatDuration(durations);
}

export function readableTimeDistance(date) {
    return formatDistanceToNow(formatDateString(date));
}

/**
 * Extract the job base name.
 */
export function jobBaseName(name) {
    if (!name.includes('\\')) return name;

    const parts = name.split('\\');

    return parts[parts.length - 1];
}
