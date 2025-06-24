function chargerMessages(id_affectation) {
    fetch(`/api/chat/${id_affectation}`)
        .then(res => res.json())
        .then(data => {
            const box = document.getElementById("chat-box");
            box.innerHTML = "";
            data.forEach(msg => {
                const div = document.createElement("div");
                div.textContent = `[${msg.date_envoi}] ${msg.contenu}`;
                box.appendChild(div);
            });
            box.scrollTop = box.scrollHeight;
        });
}

function envoyerMessage(id_ticket, id_affectation) {
    const contenu = document.getElementById("msg").value;
    if (!contenu.trim()) return;

    fetch("/api/chat/send", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ id_ticket, id_affectation, contenu })
    }).then(() => {
        document.getElementById("msg").value = "";
        chargerMessages(id_affectation);
    });
    console.log({ id_ticket, id_affectation, contenu });
}
