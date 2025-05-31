export function unixToDatetimeLocal(unix) {
    if (!unix) return '';
    return new Date(unix * 1000).toLocaleDateString('ukr-UA');
}

export function datetimeLocalToUnix(dt) {
    return dt ? Math.floor(new Date(dt).getTime() / 1000) : null;
}