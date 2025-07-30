<style>
    .click-effect {
        position: fixed;
        width: 40px;
        height: 40px;
        background: rgba(0, 0, 0, 0.2);
        border-radius: 50%;
        pointer-events: none;
        transform: scale(0);
        animation: clickAnimation 0.6s ease-out forwards;
        z-index: 9999;
    }


    @media (prefers-color-scheme: dark) {
        .click-effect {
            background: rgba(32, 131, 212, 0.6);
        }
    }

    @keyframes clickAnimation {
        to {
            transform: scale(3);
            opacity: 0;
        }
    }
</style>

<script>
    document.addEventListener("click", function(e) {
        const circle = document.createElement("span");
        circle.classList.add("click-effect");
        circle.style.left = `${e.clientX - 20}px`;
        circle.style.top = `${e.clientY - 20}px`;
        document.body.appendChild(circle);

        setTimeout(() => {
            circle.remove();
        }, 600);
    });
</script>
