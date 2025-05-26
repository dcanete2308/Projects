import Missatges from './components/Missatges/Missatges';
import VideoChat from './components/VideoChat/VideoChat';
import Header from './components/Header/Header';
import Footer from './components/Footer/Footer';
function App() {
  return (
    <div>
      <Header></Header>
      <Missatges></Missatges>
      <VideoChat />
      <Footer></Footer>
    </div>
  );
}

export default App;
