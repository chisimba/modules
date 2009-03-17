/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.server;

import avoir.realtime.common.Constants;
import avoir.realtime.common.RealtimeFile;
import avoir.realtime.common.packet.DeleteFilePacket;
import avoir.realtime.common.packet.FileDownloadRequest;
import avoir.realtime.common.packet.FileVewRequestPacket;
import avoir.realtime.common.packet.FileViewReplyPacket;
import java.awt.Graphics2D;
import java.awt.Image;
import java.awt.RenderingHints;
import java.awt.image.BufferedImage;
import java.io.File;
import java.util.ArrayList;

/**
 *
 * @author developer
 */
public class FileManagerProcessor {

    private ServerThread serverThread;

    public Image getScaledImage(Image srcImg, int w, int h) {

        BufferedImage resizedImg = new BufferedImage(w, h, BufferedImage.TYPE_INT_RGB);
        Graphics2D g2 = resizedImg.createGraphics();
        g2.setRenderingHint(RenderingHints.KEY_INTERPOLATION, RenderingHints.VALUE_INTERPOLATION_BILINEAR);
        g2.drawImage(srcImg, 0, 0, w, h, null);
        g2.dispose();
        return resizedImg;
    }

    public FileManagerProcessor(ServerThread serverThread) {
        this.serverThread = serverThread;
    }

    public void processDeleteFilePacket(DeleteFilePacket p) {
        File f = new File(p.getFilePath());
        f.delete();
        updateFileView(new File(f.getParent()).getAbsolutePath());
    }

    public void processFileDownloadRequest(FileDownloadRequest p) {
        boolean sendBackToSenderOnly = false;
        if (p.getType() == Constants.NOTEPAD) {
            sendBackToSenderOnly = true;
        }
        serverThread.getFileTransferEngine().populateBinaryFile(serverThread, p.getPath(), "", p.getType(), sendBackToSenderOnly);
    }

    public void updateFileView(String path) {
        ArrayList<RealtimeFile> fileList = new ArrayList();
        File f = new File(path);
        String[] list = f.list();
        fileList.add(new RealtimeFile(true,
                "..", f.getParent()));
        if (list != null) {
            for (int i = 0; i < list.length; i++) {
                fileList.add(new RealtimeFile(new File(path + "/" + list[i]).isDirectory(),
                        list[i], path + "/" + list[i]));
            }
        }
        serverThread.sendPacket(new FileViewReplyPacket(fileList), serverThread.getObjectOutStream());
    }

    public void processFileViewRequestPacket(FileVewRequestPacket p) {
        ArrayList<RealtimeFile> fileList = new ArrayList();

        String path = serverThread.getSERVER_HOME() + "/userfiles/" + serverThread.getThisUser().getUserName();
        if (p.getPath().endsWith(serverThread.getThisUser().getUserName())) {
            path = serverThread.getSERVER_HOME() + "/userfiles/" + serverThread.getThisUser().getUserName();
//System.out.println("1: "+path);
        } else if (p.getPath().startsWith(serverThread.getThisUser().getUserName())) {
            String pp = p.getPath();
            pp = pp.substring(pp.lastIndexOf("/"));
            path = serverThread.getSERVER_HOME() + "/userfiles/" + p.getPath(); // serverThread.getThisUser().getUserName()+"/"+pp;
            File f = new File(path);
            fileList.add(new RealtimeFile(true,
                    "..", f.getParent()));
//System.out.println("2: "+path);
        } else {
            path = p.getPath();
            File f = new File(path);
            fileList.add(new RealtimeFile(true,
                    "..", f.getParent()));
            //System.out.println("3: "+path);
        }
        //System.out.println("Filemanager: " + path);
        File f = new File(path);
        String[] list = f.list();
        java.util.Arrays.sort(list);

        if (list != null) {
            for (int i = 0; i < list.length; i++) {
                fileList.add(new RealtimeFile(new File(path + "/" + list[i]).isDirectory(),
                        list[i], path + "/" + list[i]));
            }
        }
        serverThread.sendPacket(new FileViewReplyPacket(fileList), serverThread.getObjectOutStream());
    }

    public void sendFileViewRequestPacket(String xpath) {
        ArrayList<RealtimeFile> fileList = new ArrayList();

        String path = serverThread.getSERVER_HOME() + "/userfiles/" + serverThread.getThisUser().getUserName();
        if (xpath.endsWith(serverThread.getThisUser().getUserName())) {
            path = serverThread.getSERVER_HOME() + "/userfiles/" + serverThread.getThisUser().getUserName();

        } else if (xpath.startsWith(serverThread.getThisUser().getUserName())) {
            String pp = xpath;
            pp = pp.substring(pp.lastIndexOf("/"));
            path = serverThread.getSERVER_HOME() + "/userfiles/" + xpath; // serverThread.getThisUser().getUserName()+"/"+pp;
            File f = new File(path);
            fileList.add(new RealtimeFile(true,
                    "..", f.getParent()));

        } else {
            path = xpath;
            File f = new File(path);
            fileList.add(new RealtimeFile(true,
                    "..", f.getParent()));
        }

        File f = new File(path);
        String[] list = f.list();
        java.util.Arrays.sort(list);

        if (list != null) {
            for (int i = 0; i < list.length; i++) {
                fileList.add(new RealtimeFile(new File(path + "/" + list[i]).isDirectory(),
                        list[i], path + "/" + list[i]));
            }
        }
        serverThread.sendPacket(new FileViewReplyPacket(fileList), serverThread.getObjectOutStream());
    }
}
