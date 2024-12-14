export default function withRoute() {
    const route = new URLSearchParams(window.location.search);
    return {
        route 
    }
}