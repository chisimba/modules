/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.avoir.realtime.gui;

import java.util.ArrayList;
import javax.swing.tree.DefaultMutableTreeNode;
import org.avoir.realtime.common.RealtimeFile;
import org.avoir.realtime.gui.dnd.RSlidesTreeTransferHandler;
import org.avoir.realtime.gui.main.MainFrame;
import org.avoir.realtime.net.RPacketListener;
import org.avoir.realtime.net.SlideShowListener;
import org.avoir.realtime.net.packets.RealtimeQuestionPacket;
import org.avoir.realtime.net.packets.RealtimeSlideShowPacket;
import org.avoir.realtime.net.providers.DownloadedImage;
import org.avoir.realtime.slidebuilder.Slide;
import org.avoir.realtime.slidebuilder.SlideBuilderManager;

/**
 *
 * @author developer
 */
public class SlidesNavigator extends Navigator implements SlideShowListener {

    public SlidesNavigator(MainFrame xmf) {
        super(xmf, "slideshows", "Slides Editor");
        RPacketListener.addSlidesNavigatorFileVewListener(this);
        RPacketListener.addSlideShowListener(this);
        tree.setDragEnabled(true);
        tree.setTransferHandler(new RSlidesTreeTransferHandler());
        tree.setShowsRootHandles(true);
    }

    public void addSlideShowAsRoomResource(RealtimeSlideShowPacket packet) {

        String showName = packet.getFilename();
        

        RealtimeFile f = new RealtimeFile(showName, packet.getFilePath(), false, false);
        DefaultMutableTreeNode p = mf.getRoomResourceNavigator().addResource(f);
        ArrayList<Slide> slides = packet.getSlides();
        for (Slide slide : slides) {
            RealtimeFile rs = new RealtimeFile(slide.getTitle(), packet.getFilePath(), false, false);
            mf.getRoomResourceNavigator().addResource(p, rs);
        }

    }

    private void showSlideShowFrame(RealtimeSlideShowPacket p) {

        final SlideBuilderManager fr = new SlideBuilderManager();
        fr.setSlideShowName(p.getFilename());
        fr.setSlides(p.getSlides());
        fr.setSlideShowPath(p.getFilePath());
        fr.setAccess(p.getAccess());
        fr.setSize((ss.width / 8) * 7, (ss.height / 8) * 7);
        fr.setLocationRelativeTo(null);
        fr.setVisible(true);

    }

    public void openSlideShow(RealtimeSlideShowPacket packet) {
        showSlideShowFrame(packet);
    }

    public void processSlideShowImage(DownloadedImage downloadedImage) {
        System.out.println("This also should not be coming here at all");
    }

    public void processSlideShowQuestion(RealtimeQuestionPacket packet) {
        System.out.println("This should not be coming here at all");
    }
}
