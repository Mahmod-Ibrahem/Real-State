export default {
    install: () => {
        if (!window.Tawk_API) {
            const s1 = document.createElement("script");
            s1.async = true;
            s1.src = "https://embed.tawk.to/683e9ae12ab8b2190c8286c5/1isq8q0tu";
            s1.charset = "UTF-8";
            s1.setAttribute("crossorigin", "*");
            document.head.appendChild(s1);
        }
    }
};

