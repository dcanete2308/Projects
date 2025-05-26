import React, { useRef, useState, useEffect } from 'react';
import styles from './videochat.module.scss';
// WebRTCComponent
const VideoChat = () => {
  const localVideoRef = useRef(null);
  const remoteVideoRef = useRef(null);
  const [startDisabled, setStartDisabled] = useState(false);
  const [hangupDisabled, setHangupDisabled] = useState(true);

  const pcRef = useRef(null); // conexion p2p
  const localStreamRef = useRef(null);
  const signaling = useRef(new BroadcastChannel('webrtc')); // esto usa RTC para la comunciación entre los navegadores

  useEffect(() => {
    const channel = signaling.current; // accede al canal

    channel.onmessage = async (e) => { // se activa cuando recibe un mensaje

      if (!localStreamRef.current) {
        console.log('[SIGNALING] Esperando a que localStream esté listo...');
        return;
      }

      const data = e.data; // datos del otro user de la llamada

      // switch para ver controlar cada caso
      switch (data.type) {
        case 'offer':
          await handleOffer(data);
          break;
        case 'answer':
          await handleAnswer(data);
          break;
        case 'candidate':
          await handleCandidate(data);
          break;
        case 'ready':
          if (pcRef.current) {
            return;
          }
          await makeCall();
          break;
        case 'bye':
          if (pcRef.current) {
            await hangup();
          }
          break;
        default:
      }
    };

    return () => {
    };
  }, []);

  const safePostMessage = (message) => { // envia un mensaje de forma segura
    try {
      signaling.current?.postMessage(message);
    } catch (e) {
      console.log(e) // pilla los errores
    }
  };

  // funcion que inicia la llamada
  const start = async () => {
    const stream = await navigator.mediaDevices.getUserMedia({ audio: true, video: true }); // del navegador coge el auido y el video
    localStreamRef.current = stream; // lo adjunta al pc local
    localVideoRef.current.srcObject = stream;

    setStartDisabled(true);
    setHangupDisabled(false);

    safePostMessage({ type: 'ready' }); // llama al switch
  };

  // cuelga la llamada
  const hangup = async () => {
    if (pcRef.current) {
      pcRef.current.close(); // cierra la conexion WebRTC
      pcRef.current = null; // limpia la referencia
    }

    if (localStreamRef.current) {
      localStreamRef.current.getTracks().forEach((track) => track.stop()); // detiene el local y limpia la referencia
      localStreamRef.current = null;
    }

    setStartDisabled(false);
    setHangupDisabled(true);
    safePostMessage({ type: 'bye' });
    location.reload()
  };

  // crea una conexion WebRTC
  const createPeerConnection = () => {
    const pc = new RTCPeerConnection();

    pc.onicecandidate = (e) => { // consigue el candidato para hacer la llamdada
      const message = {
        type: 'candidate',
        candidate: e.candidate?.candidate || null,
        sdpMid: e.candidate?.sdpMid,
        sdpMLineIndex: e.candidate?.sdpMLineIndex,
      };
      safePostMessage(message); // envia el mensaje
    };

    pc.ontrack = (e) => { // se activa cuando recibe videos o audios y lo pone en el remoto
      remoteVideoRef.current.srcObject = e.streams[0];
    };

    localStreamRef.current.getTracks().forEach((track) => { // añade los audio o videos del lcal
      pc.addTrack(track, localStreamRef.current);
    });

    pcRef.current = pc;
  };

  // hace la llamdaa
  const makeCall = async () => {
    createPeerConnection(); // crea la conexion WebRTC
    const offer = await pcRef.current.createOffer();
    await pcRef.current.setLocalDescription(offer);
    safePostMessage({ type: 'offer', sdp: offer.sdp }); // envia la oferta al participante
  };

  const handleOffer = async (offer) => { // maneja la offerta del cliente
    if (pcRef.current) {
      return;
    }
    createPeerConnection(); // crea la connexion WebRTC
    await pcRef.current.setRemoteDescription(new RTCSessionDescription({ type: 'offer', sdp: offer.sdp })); // crea una oferta para el pc remoto
    const answer = await pcRef.current.createAnswer();
    await pcRef.current.setLocalDescription(answer); // espera la respuesta del pc local
    safePostMessage({ type: 'answer', sdp: answer.sdp }); // envia la respuesta al participante
  };

  // maneja la respuesta 
  const handleAnswer = async (answer) => {
    if (!pcRef.current) {
      return;
    }
    await pcRef.current.setRemoteDescription(new RTCSessionDescription({ type: 'answer', sdp: answer.sdp })); // establece la respuesta
  };

  const handleCandidate = async (candidate) => {
    if (!pcRef.current) {
      return;
    }
    if (!candidate.candidate) {
      await pcRef.current.addIceCandidate(null);
    } else {
      await pcRef.current.addIceCandidate(new RTCIceCandidate(candidate));
    }
  };

  return (
    <>
      <div className={styles.video}>
        <video className={styles.videolocal} ref={localVideoRef} autoPlay playsInline muted></video>
        <video className={styles.videoremoto} ref={remoteVideoRef} autoPlay playsInline></video>
      </div>
      <div className={styles.buttons}>
        <button onClick={start} disabled={startDisabled}>Start</button>
        <button onClick={() => { hangup(); safePostMessage({ type: 'bye' }); }} disabled={hangupDisabled}>Hang Up</button>
      </div>
    </>
  );

};

export default VideoChat;
