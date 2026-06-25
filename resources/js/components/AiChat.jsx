import React, { useEffect, useRef, useState } from "react";

export default function AiChat() {
    const [open, setOpen] = useState(false);
    const [message, setMessage] = useState("");
    const [iconFailed, setIconFailed] = useState(false);
    const [position, setPosition] = useState(() => ({
        x: 28,
        y: typeof window === "undefined" ? 520 : window.innerHeight - 96,
    }));
    const [messages, setMessages] = useState([
        {
            role: "bot",
            text: "Hola, soy el asistente de JM y JS Alimentos. En que puedo ayudarte?",
        },
    ]);
    const [loading, setLoading] = useState(false);
    const bottomRef = useRef(null);
    const chatRef = useRef(null);
    const cloudRef = useRef(null);
    const dragRef = useRef({
        dragging: false,
        moved: false,
        offsetX: 0,
        offsetY: 0,
        startX: 0,
        startY: 0,
    });

    const clamp = (value, min, max) => Math.min(Math.max(value, min), max);
    const assistantIcon = "/img/ai-assistant.ico";
    const renderAssistantIcon = (size = 30) => iconFailed ? (
        <span style={{
            width: `${size}px`,
            height: `${size}px`,
            borderRadius: "50%",
            display: "inline-flex",
            alignItems: "center",
            justifyContent: "center",
            background: "rgba(255,255,255,0.20)",
            color: "inherit",
            fontSize: `${Math.max(10, Math.round(size * 0.36))}px`,
            fontWeight: 700,
            lineHeight: 1,
        }}>
            IA
        </span>
    ) : (
        <img
            src={assistantIcon}
            alt=""
            aria-hidden="true"
            draggable="false"
            onError={() => setIconFailed(true)}
            style={{
                width: `${size}px`,
                height: `${size}px`,
                borderRadius: "50%",
                objectFit: "cover",
                display: "block",
                background: "rgba(255,255,255,0.18)",
                boxShadow: "inset 0 0 0 1px rgba(255,255,255,0.22)",
            }}
        />
    );

    useEffect(() => {
        if (open) bottomRef.current?.scrollIntoView({ behavior: "smooth" });
    }, [messages, open]);

    useEffect(() => {
        const keepInsideViewport = () => {
            setPosition((current) => ({
                x: clamp(current.x, 12, window.innerWidth - 86),
                y: clamp(current.y, 12, window.innerHeight - 76),
            }));
        };

        window.addEventListener("resize", keepInsideViewport);
        return () => window.removeEventListener("resize", keepInsideViewport);
    }, []);

    useEffect(() => {
        if (!open) return undefined;

        const closeOnOutsidePointer = (e) => {
            if (chatRef.current?.contains(e.target) || cloudRef.current?.contains(e.target)) {
                return;
            }

            setOpen(false);
        };

        document.addEventListener("pointerdown", closeOnOutsidePointer);
        return () => document.removeEventListener("pointerdown", closeOnOutsidePointer);
    }, [open]);

    const sendMessage = async () => {
        if (!message.trim() || loading) return;

        const userMsg = message.trim();
        setMessages((prev) => [...prev, { role: "user", text: userMsg }]);
        setMessage("");
        setLoading(true);

        try {
            const response = await fetch("/api/chat", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')?.content || "",
                },
                body: JSON.stringify({
                    message: userMsg,
                    history: messages.slice(-8),
                }),
            });
            const data = await response.json();
            setMessages((prev) => [
                ...prev,
                {
                    role: "bot",
                    text: data.reply || (response.ok ? "Sin respuesta." : "No pude responder en este momento."),
                },
            ]);
        } catch {
            setMessages((prev) => [
                ...prev,
                { role: "bot", text: "Error al conectar. Intenta de nuevo." },
            ]);
        } finally {
            setLoading(false);
        }
    };

    const handleKey = (e) => {
        if (e.key === "Enter" && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    };

    const handleCloudPointerDown = (e) => {
        if (e.button !== undefined && e.button !== 0) return;

        const rect = e.currentTarget.getBoundingClientRect();
        dragRef.current = {
            dragging: true,
            moved: false,
            offsetX: e.clientX - rect.left,
            offsetY: e.clientY - rect.top,
            startX: e.clientX,
            startY: e.clientY,
        };
        e.currentTarget.setPointerCapture?.(e.pointerId);
    };

    const handleCloudPointerMove = (e) => {
        if (!dragRef.current.dragging) return;

        const deltaX = Math.abs(e.clientX - dragRef.current.startX);
        const deltaY = Math.abs(e.clientY - dragRef.current.startY);
        if (deltaX > 4 || deltaY > 4) {
            dragRef.current.moved = true;
        }

        setPosition({
            x: clamp(e.clientX - dragRef.current.offsetX, 12, window.innerWidth - 86),
            y: clamp(e.clientY - dragRef.current.offsetY, 12, window.innerHeight - 76),
        });
    };

    const handleCloudPointerUp = (e) => {
        if (!dragRef.current.dragging) return;

        e.currentTarget.releasePointerCapture?.(e.pointerId);
        const wasMoved = dragRef.current.moved;
        dragRef.current.dragging = false;
        dragRef.current.moved = false;

        if (!wasMoved) {
            setOpen(true);
        }
    };

    const chatLeft = typeof window === "undefined"
        ? position.x
        : clamp(position.x, 12, window.innerWidth - 352);
    const preferredChatTop = position.y > 530 ? position.y - 514 : position.y + 82;
    const chatTop = typeof window === "undefined"
        ? preferredChatTop
        : clamp(preferredChatTop, 12, window.innerHeight - 512);

    return (
        <>
            <button
                ref={cloudRef}
                aria-label={open ? "Cerrar asistente IA" : "Abrir asistente IA"}
                className={`ai-assistant-button ${open ? "is-open" : ""}`}
                onPointerDown={handleCloudPointerDown}
                onPointerMove={handleCloudPointerMove}
                onPointerUp={handleCloudPointerUp}
                onPointerCancel={handleCloudPointerUp}
                style={{
                    position: "fixed",
                    left: `${position.x}px`,
                    top: `${position.y}px`,
                    width: open ? "58px" : "174px",
                    height: "58px",
                    borderRadius: open ? "50%" : "999px",
                    border: "none",
                    color: "#fff",
                    background: "linear-gradient(135deg, #38BDF8, #0284C7)",
                    cursor: dragRef.current.dragging ? "grabbing" : "grab",
                    zIndex: 9999,
                    display: "flex",
                    alignItems: "center",
                    justifyContent: "center",
                    gap: "8px",
                    padding: "0 18px",
                    userSelect: "none",
                    touchAction: "none",
                    boxShadow: "0 10px 28px rgba(2,132,199,0.38)",
                    animation: open ? "none" : "pulse-btn 2s infinite",
                    transition: dragRef.current.dragging ? "none" : "width 0.25s ease, border-radius 0.25s ease, transform 0.2s ease",
                }}
            >
                <>
                    <span className="ai-cloud-content">{renderAssistantIcon(open ? 34 : 30)}</span>
                    {!open && (
                        <span className="ai-cloud-content" style={{
                            color: "#fff",
                            fontSize: "14px",
                            fontWeight: 600,
                            fontFamily: "inherit",
                            whiteSpace: "nowrap",
                        }}>
                            Asistente IA
                        </span>
                    )}
                </>
            </button>

            {open && (
                <div ref={chatRef} style={{
                    position: "fixed",
                    top: `${chatTop}px`,
                    left: `${chatLeft}px`,
                    width: "340px",
                    maxHeight: "500px",
                    background: "#ffffff",
                    borderRadius: "20px",
                    boxShadow: "0 12px 48px rgba(2,132,199,0.20)",
                    display: "flex",
                    flexDirection: "column",
                    zIndex: 9998,
                    overflow: "hidden",
                    border: "1px solid rgba(2,132,199,0.10)",
                    animation: "slideUp 0.25s ease",
                }}>
                    <div style={{
                        background: "linear-gradient(135deg, #0284C7, #075985)",
                        padding: "16px 18px",
                        display: "flex",
                        alignItems: "center",
                        gap: "12px",
                    }}>
                        <div style={{
                            width: "40px",
                            height: "40px",
                            borderRadius: "50%",
                            background: "rgba(255,255,255,0.15)",
                            display: "flex",
                            alignItems: "center",
                            justifyContent: "center",
                            flexShrink: 0,
                            overflow: "hidden",
                        }}>{renderAssistantIcon(38)}</div>
                        <div>
                            <div style={{ color: "#fff", fontWeight: 500, fontSize: "15px" }}>
                                Asistente JM y JS
                            </div>
                            <div style={{ display: "flex", alignItems: "center", gap: "5px", marginTop: "2px" }}>
                                <div style={{ width: "7px", height: "7px", borderRadius: "50%", background: "#BAE6FD" }}></div>
                                <span style={{ color: "rgba(255,255,255,0.75)", fontSize: "12px" }}>En linea</span>
                            </div>
                        </div>
                    </div>

                    <div style={{
                        flex: 1,
                        overflowY: "auto",
                        padding: "16px",
                        display: "flex",
                        flexDirection: "column",
                        gap: "10px",
                        background: "#EAF7FF",
                    }}>
                        {messages.map((msg, i) => (
                            <div key={i} style={{
                                display: "flex",
                                justifyContent: msg.role === "user" ? "flex-end" : "flex-start",
                            }}>
                                {msg.role === "bot" && (
                                    <div style={{
                                        width: "28px",
                                        height: "28px",
                                        borderRadius: "50%",
                                        background: iconFailed ? "#DFF3FF" : "#ffffff",
                                        color: "#075985",
                                        flexShrink: 0,
                                        display: "flex",
                                        alignItems: "center",
                                        justifyContent: "center",
                                        marginRight: "7px",
                                        marginTop: "2px",
                                        overflow: "hidden",
                                        border: "1px solid rgba(2,132,199,0.10)",
                                    }}>{renderAssistantIcon(26)}</div>
                                )}
                                <div style={{
                                    maxWidth: "75%",
                                    background: msg.role === "user" ? "#0284C7" : "#ffffff",
                                    color: msg.role === "user" ? "#fff" : "#0B2538",
                                    padding: "10px 14px",
                                    borderRadius: msg.role === "user"
                                        ? "18px 18px 4px 18px"
                                        : "18px 18px 18px 4px",
                                    fontSize: "13.5px",
                                    lineHeight: "1.55",
                                    border: msg.role === "bot" ? "1px solid rgba(2,132,199,0.08)" : "none",
                                    boxShadow: "0 2px 8px rgba(0,0,0,0.05)",
                                }}>
                                    {msg.text}
                                </div>
                            </div>
                        ))}

                        {loading && (
                            <div style={{ display: "flex", alignItems: "center", gap: "7px" }}>
                                <div style={{
                                    width: "28px",
                                    height: "28px",
                                    borderRadius: "50%",
                                    background: iconFailed ? "#DFF3FF" : "#ffffff",
                                    color: "#075985",
                                    display: "flex",
                                    alignItems: "center",
                                    justifyContent: "center",
                                    overflow: "hidden",
                                    border: "1px solid rgba(2,132,199,0.10)",
                                }}>{renderAssistantIcon(26)}</div>
                                <div style={{
                                    background: "#fff",
                                    border: "1px solid rgba(2,132,199,0.08)",
                                    borderRadius: "18px 18px 18px 4px",
                                    padding: "10px 16px",
                                    display: "flex",
                                    gap: "5px",
                                }}>
                                    {[0, 1, 2].map((n) => (
                                        <div key={n} style={{
                                            width: "7px",
                                            height: "7px",
                                            borderRadius: "50%",
                                            background: "#7DD3FC",
                                            animation: `bounce 1s ${n * 0.15}s infinite`,
                                        }} />
                                    ))}
                                </div>
                            </div>
                        )}
                        <div ref={bottomRef} />
                    </div>

                    <div className="ai-suggestions" style={{
                        padding: "10px 12px",
                        display: "flex",
                        flexWrap: "nowrap",
                        justifyContent: "flex-start",
                        gap: "8px",
                        overflowX: "auto",
                        overflowY: "hidden",
                        background: "#fff",
                        borderTop: "1px solid rgba(2,132,199,0.06)",
                        scrollbarWidth: "none",
                    }}>
                        {["Que cursos ofrecen?", "Precios", "Como me inscribo?"].map((suggestion) => (
                            <button
                                key={suggestion}
                                onClick={() => setMessage(suggestion)}
                                style={{
                                    background: "#DFF3FF",
                                    color: "#075985",
                                    border: "1px solid rgba(2,132,199,0.15)",
                                    borderRadius: "999px",
                                    padding: "5px 14px",
                                    minHeight: "24px",
                                    fontSize: "12px",
                                    lineHeight: "1.1",
                                    fontWeight: 500,
                                    cursor: "pointer",
                                    whiteSpace: "nowrap",
                                    boxShadow: "inset 0 1px 0 rgba(255,255,255,0.7)",
                                    transition: "all 0.2s",
                                }}
                            >
                                {suggestion}
                            </button>
                        ))}
                    </div>

                    <div style={{
                        padding: "12px",
                        background: "#fff",
                        borderTop: "1px solid rgba(2,132,199,0.08)",
                        display: "flex",
                        gap: "8px",
                        alignItems: "flex-end",
                    }}>
                        <textarea
                            value={message}
                            onChange={(e) => setMessage(e.target.value)}
                            onKeyDown={handleKey}
                            placeholder="Escribe tu consulta..."
                            rows={1}
                            style={{
                                flex: 1,
                                border: "1.5px solid #DFF3FF",
                                borderRadius: "12px",
                                padding: "10px 12px",
                                fontSize: "13px",
                                fontFamily: "inherit",
                                resize: "none",
                                outline: "none",
                                background: "#EAF7FF",
                                color: "#0B2538",
                                transition: "border-color 0.2s",
                                lineHeight: "1.4",
                            }}
                            onFocus={(e) => e.target.style.borderColor = "#0284C7"}
                            onBlur={(e) => e.target.style.borderColor = "#DFF3FF"}
                        />
                        <button
                            onClick={sendMessage}
                            disabled={loading || !message.trim()}
                            style={{
                                width: "40px",
                                height: "40px",
                                borderRadius: "12px",
                                background: message.trim() ? "#0284C7" : "#DFF3FF",
                                border: "none",
                                cursor: message.trim() ? "pointer" : "default",
                                display: "flex",
                                alignItems: "center",
                                justifyContent: "center",
                                transition: "all 0.2s",
                                flexShrink: 0,
                            }}
                        >
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                                <path
                                    d="M22 2L11 13M22 2L15 22L11 13M11 13L2 9L22 2"
                                    stroke={message.trim() ? "white" : "#7DD3FC"}
                                    strokeWidth="2"
                                    strokeLinecap="round"
                                    strokeLinejoin="round"
                                />
                            </svg>
                        </button>
                    </div>
                </div>
            )}

            <style>{`
                .ai-assistant-button:active {
                    cursor: grabbing;
                }

                .ai-assistant-button:hover {
                    transform: translateY(-2px);
                }

                .ai-cloud-content {
                    position: relative;
                    z-index: 1;
                    pointer-events: none;
                }

                .ai-suggestions::-webkit-scrollbar {
                    display: none;
                }

                @keyframes slideUp {
                    from { opacity: 0; transform: translateY(20px); }
                    to { opacity: 1; transform: translateY(0); }
                }

                @keyframes bounce {
                    0%, 100% { transform: translateY(0); }
                    50% { transform: translateY(-5px); }
                }

                @keyframes pulse-btn {
                    0%, 100% { filter: drop-shadow(0 4px 18px rgba(2,132,199,0.45)); }
                    50% { filter: drop-shadow(0 4px 28px rgba(2,132,199,0.74)); }
                }
            `}</style>
        </>
    );
}
