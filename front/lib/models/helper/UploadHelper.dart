import 'dart:io';

import 'package:dio/dio.dart';
import 'package:file_picker/file_picker.dart';
import 'package:flutter/material.dart';
import 'package:front/models/core/Folder.dart';
import 'package:front/models/core/User.dart';
import 'package:front/models/helper/FolderHelper.dart';
import 'package:front/models/service/UploadFileApi.dart';

class UploadFileHelper {
  late BuildContext context;

  late FolderHelper folderHelper;

  UploadFileApi uploadFileApi = UploadFileApi();

  UploadFileHelper({required this.context}) {
    folderHelper = FolderHelper(context: context);
  }

  Future<void> uploadFile(
      {required List<PlatformFile> listFiles,
      required User user,
      required Folder folder}) async {
    for (PlatformFile file in listFiles) {
      var formData = FormData.fromMap({
        'file': await MultipartFile.fromFile(file.path!, filename: file.name)
      });
      await uploadFileApi.uploadFile(
          formData: formData, user: user, idFolder: folder.id);
    }
    folderHelper.getFolderContent(folder: folder);
  }
}
